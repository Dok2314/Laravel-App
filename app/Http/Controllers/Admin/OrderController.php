<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\DTO\OrderDTO;
use App\Services\OrderService;
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

        $orders = Order::paginate((int)$perPage);

        return response()->json($orders);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $orderDTO = OrderDTO::fromArray($validatedData);

        $order = $this->orderService->createOrder($orderDTO);

        return response()->json(['message' => __('order_messages.order_created'), 'order' => $order], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $orderDTO = OrderDTO::fromArray($validatedData);

            $order = $this->orderService->updateOrder($id, $orderDTO);

            return response()->json(['message' => __('order_messages.order_updated'), 'order' => $order]);
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
