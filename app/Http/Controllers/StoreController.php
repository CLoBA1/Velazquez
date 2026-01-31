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
        // 1. Featured Offers (Top 5 Highest Discount)
        $featured_offers = Product::hardware()
            ->with('category')
            ->where('stock', '>', 0)
            ->whereNotNull('sale_price')
            ->whereColumn('sale_price', '<', 'public_price')
            ->orderByRaw('(public_price - sale_price) DESC')
            ->take(5)
            ->get();

        // 2. New Arrivals (Latest 3)
        $new_arrivals = Product::hardware()
            ->where('stock', '>', 0)
            ->latest()
            ->take(3)
            ->get();

        // 3. Category Highlight (Random category with products)
        $category_highlight = Category::whereHas('products', function ($q) {
            $q->hardware()->where('stock', '>', 0);
        })->inRandomOrder()->first();

        return view('store.index', compact('featured_offers', 'new_arrivals', 'category_highlight'));
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
