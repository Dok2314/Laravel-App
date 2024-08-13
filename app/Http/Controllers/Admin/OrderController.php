<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\DTO\OrderDTO;
use App\Services\OrderService;
use App\Http\Resources\OrderResource;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        if (!is_numeric($perPage) || $perPage <= 0) {
            return response()->json(['message' => __('validation_messages.per_page')], 400);
        }

        $orders = Order::with(['product', 'user'])->paginate((int)$perPage);

        return OrderResource::collection($orders);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $orderDTO = OrderDTO::fromArray($validatedData);

        $order = $this->orderService->createOrder($orderDTO);

        return new OrderResource($order);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $orderDTO = OrderDTO::fromArray($validatedData);

            $order = $this->orderService->updateOrder($id, $orderDTO);

            return new OrderResource($order);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder($id);

            return response()->json(['message' => __('order_messages.order_deleted')]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
