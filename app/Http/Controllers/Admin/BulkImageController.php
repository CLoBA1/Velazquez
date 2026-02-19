<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulkImageController extends Controller
{
    /**
     * Show the bulk image assignment form.
     */
    public function index()
    {
        return view('admin.products.bulk-image');
    }

    /**
     * Preview products matching the search query.
     */
    public function preview(Request $request)
    {
        $search = trim($request->get('search'));

        if (strlen($search) < 3) {
            return response()->json(['products' => [], 'message' => 'Ingrese al menos 3 caracteres.']);
        }

        $products = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->select('id', 'name', 'internal_code', 'main_image_path')
            ->limit(50)
            ->get();

        return response()->json([
            'products' => $products,
            'count' => $products->count()
        ]);
    }

    /**
     * Apply image to filtered products.
     */
    public function update(Request $request)
    {
        $request->validate([
            'search_term' => 'required|min:3',
            'image' => 'required|image|max:4096', // 4MB Max
        ]);

        $search = trim($request->input('search_term'));
        
        // 1. Upload the image once
        $path = $request->file('image')->store('products', 'public');

        // 2. Update all matching products
        $affected = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->update(['main_image_path' => $path]);

        return redirect()->route('admin.products.bulk-image.index')
            ->with('success', "Imagen aplicada a {$affected} productos correctamente.");
    }
}
