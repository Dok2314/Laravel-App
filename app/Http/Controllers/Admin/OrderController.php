<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Order;
use App\DTO\OrderDTO;
use App\Services\OrderService;
use Exception;

class OrderController extends Controller
{
    public function __construct(
      private readonly OrderService $orderService
    ) {
    }

    public function index()
    {
        $orders = Order::paginate(10);
        return response()->json($orders);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $orderDTO = OrderDTO::fromArray($validatedData);

        $order = $this->orderService->createOrder($orderDTO);

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $orderDTO = OrderDTO::fromArray($validatedData);

            $order = $this->orderService->updateOrder($id, $orderDTO);

            return response()->json(['message' => 'Order updated successfully', 'order' => $order]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder($id);

            return response()->json(['message' => 'Order deleted successfully']);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
