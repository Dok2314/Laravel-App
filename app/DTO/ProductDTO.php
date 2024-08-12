<?php

namespace App\DTO;

readonly class ProductDTO
{
    public function __construct(
        private string $name,
        private float $price,
        private ?string $description = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? '',
            price: $data['price'] ?? 0.0,
            description: $data['description'] ?? null
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'description' => $this->getDescription(),
        ];
    }
}
