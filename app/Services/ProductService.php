<?php

namespace App\Services;

use App\DTO\ProductDTO;
use App\Models\Product;
use Exception;

class ProductService
{
    public function createProduct(ProductDTO $productDTO): Product
    {
        return Product::create($productDTO->toArray());
    }

    public function updateProduct(int $id, ProductDTO $productDTO)
    {
        $product = Product::find($id);

        if (!$product) {
            throw new Exception(__('product_messages.product_not_found'));
        }

        $product->name = $productDTO->getName();
        $product->price = $productDTO->getPrice();
        $product->description = $productDTO->getDescription();
        $product->save();

        return $product;
    }

    public function deleteProduct(int $id): void
    {
        $product = Product::find($id);

        if (!$product) {
            throw new Exception(__('product_messages.product_not_found'));
        }

        $product->delete();
    }
}
