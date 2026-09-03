<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    public function index()
    {
        $products = Cache::remember(
            'products:all',
            now()->addMinutes(10),
            function () {
                return Product::all()->toArray();
            }
        );

        return response()->json($products);
    }

    public function show(string $id)
    {
        $product = Cache::remember(
            "product:{$id}",
            now()->addMinutes(10),
            function () use ($id) {
                return Product::findOrFail($id);
            }
        );

        return response()->json($product);
    }
}