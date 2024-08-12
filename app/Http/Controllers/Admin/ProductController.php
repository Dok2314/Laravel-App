<?php

namespace App\Http\Controllers\Admin;

use App\DTO\ProductDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService
    ) {
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        if (!is_numeric($perPage) || $perPage <= 0) {
            return response()->json(['message' => __('validation_messages.per_page')], 400);
        }

        $products = Product::paginate((int)$perPage);

        return response()->json($products);
    }


    public function store(StoreRequest $request)
    {
        $validatedData = $request->validated();

        $productDTO = ProductDTO::fromArray($validatedData);

        $product = $this->productService->createProduct($productDTO);

        return response()->json(['message' => __('product_messages.product_created'), 'product' => $product], 201);
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $productDTO = ProductDTO::fromArray($validatedData);

            $product = $this->productService->updateProduct($id, $productDTO);

            return response()->json(['message' => __('product_messages.product_updated'), 'product' => $product]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return response()->json(['message' => __('product_messages.product_deleted')]);
        } catch (Exception $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }
}
