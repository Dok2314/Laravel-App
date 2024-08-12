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
            throw new Exception(__('order_messages.order_not_found'));
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
            throw new Exception(__('order_messages.order_not_found'));
        }

        $order->delete();
    }
}
