<?php

namespace App\DTO;

readonly class OrderDTO
{
    public function __construct(
        private int $userId,
        private int $productId,
        private int $quantity
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'] ?? 0,
            productId: $data['product_id'] ?? 0,
            quantity: $data['quantity'] ?? 0
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'product_id' => $this->getProductId(),
            'quantity' => $this->getQuantity(),
        ];
    }
}

