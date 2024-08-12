<?php

namespace App\Services;

use App\DTO\OrderDTO;
use App\Models\Order;
use Exception;

class OrderService
{
    public function createOrder(OrderDTO $orderDTO): Order
    {
        return Order::create($orderDTO->toArray());
    }

    public function updateOrder(int $id, OrderDTO $orderDTO)
    {
        $order = Order::find($id);

        if (!$order) {
            throw new Exception('Order not found');
        }

        $order->user_id = $orderDTO->getUserId();
        $order->product_id = $orderDTO->getProductId();
        $order->quantity = $orderDTO->getQuantity();
        $order->save();

        return $order;
    }

    public function deleteOrder(int $id): void
    {
        $order = Order::find($id);

        if (!$order) {
            throw new Exception('Product not found');
        }

        $order->delete();
    }
}
