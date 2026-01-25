<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::withCount('products')->orderBy('name')->get();
        return view('store.brands.index', compact('brands'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Using ID for now, can support slug later if needed
        $brand = Brand::with('products')->findOrFail($id);
        $products = $brand->products()->where('stock', '>', 0)->paginate(12);

        return view('store.brands.show', compact('brand', 'products'));
    }
}
