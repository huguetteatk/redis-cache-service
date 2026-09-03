<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductController
{
    public function index()
    {
        $products = Cache::store('redis')->remember(
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
        $product = Cache::store('redis')->remember(
            "product:{$id}",
            now()->addMinutes(10),
            function () use ($id) {
                return Product::findOrFail($id)->toArray();
            }
        );

        return response()->json($product);
    }
}
