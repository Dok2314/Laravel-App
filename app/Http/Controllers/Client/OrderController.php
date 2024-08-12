<?php

namespace App\Http\Controllers\Client;

use App\DTO\OrderDTO;
use App\Http\Requests\Order\StoreRequest;
use App\Http\Controllers\Controller;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $orderDTO = OrderDTO::fromArray($validatedData);

        $order = $this->orderService->createOrder($orderDTO);

        return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    }
}
