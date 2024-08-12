<?php

namespace App\Http\Controllers\Admin;

use App\DTO\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }

    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $productDTO = ProductDTO::fromArray($validatedData);

        $product = $this->productService->createProduct($productDTO);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $productDTO = ProductDTO::fromArray($validatedData);

            $product = $this->productService->updateProduct($id, $productDTO);

            return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
