<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Category;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('category')) {
            $query->where('category_id', $request->input('category'));
        }

        $products = $query->paginate(100);
        $categories = Category::all();

        // 1. Featured Offer (Highest Discount)
        $featured_offer = Product::where('stock', '>', 0)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'public_price')
            ->orderByRaw('(public_price - sale_price) DESC')
            ->first();

        // 2. New Arrival (Latest Created)
        $new_arrival = Product::where('stock', '>', 0)
            ->latest()
            ->first();

        // 3. Category Highlight (Random category with products)
        $category_highlight = Category::whereHas('products', function ($q) {
            $q->where('stock', '>', 0);
        })->inRandomOrder()->first();

        return view('store.index', compact('products', 'categories', 'featured_offer', 'new_arrival', 'category_highlight'));
    }

    public function cart()
    {
        return view('store.cart');
    }

    public function show($product) // Use slug or ID? Let's use ID or Slug logic if implemented.
    {
        // For now, accept ID directly or Slug if model binding works
        // If route defines {product}, Laravel tries ID default.

        $product = Product::with(['inventoryMovements', 'category', 'brand'])->findOrFail($product);

        return view('store.show', compact('product'));
    }
}
