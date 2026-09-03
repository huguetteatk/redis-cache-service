<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController
{
    public function index()
    {
        $categories = Cache::remember(
            'categories:all',
            now()->addMinutes(10),
            function () {
                return Category::all()->toArray();
            }
        );

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        Cache::forget('categories:all');

        return response()->json($category, 201);
    }
}