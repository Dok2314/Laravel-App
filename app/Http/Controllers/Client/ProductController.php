<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        if (!is_numeric($perPage) || $perPage <= 0) {
            return response()->json(['message' => 'Invalid per_page value'], 400);
        }

        $products = Product::orderBy('created_at', 'desc')->paginate((int)$perPage);

        return response()->json($products);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }
}

