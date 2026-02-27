<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BulkActionController extends Controller
{
    /**
     * Show the bulk action form.
     */
    public function index()
    {
        // Fetch brands and units for the dropdowns
        $brands = Brand::orderBy('name')->get();
        $units = \App\Models\Unit::orderBy('name')->get();
        return view('admin.products.bulk-actions', compact('brands', 'units'));
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
     * Apply action to filtered products.
     */
    public function update(Request $request)
    {
        $request->validate([
            'search_term' => 'required|min:3',
            'action_type' => 'required|in:assign_image,assign_brand,remove_image,assign_unit',
            'image' => 'required_if:action_type,assign_image|image|max:4096',
            'brand_id' => 'required_if:action_type,assign_brand|exists:brands,id',
            'unit_id' => 'required_if:action_type,assign_unit|exists:units,id',
        ]);

        $search = trim($request->input('search_term'));
        $action = $request->input('action_type');

        $query = Product::where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");

        $affected = 0;

        if ($action === 'assign_image') {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $affected = $query->update(['main_image_path' => $path]);
            }
        } elseif ($action === 'assign_brand') {
            $brandId = $request->input('brand_id');
            $affected = $query->update(['brand_id' => $brandId]);
        } elseif ($action === 'assign_unit') {
            $unitId = $request->input('unit_id');
            $affected = $query->update(['unit_id' => $unitId]);
        } elseif ($action === 'remove_image') {
            // Note: This doesn't delete the file from storage, only the reference.
            // Ideally we would delete files, but for bulk updates checking each one is expensive.
            $affected = $query->update(['main_image_path' => null]);
        }

        return redirect()->route('admin.bulk-actions.index')
            ->with('success', "Acción '{$action}' aplicada a {$affected} productos correctamente.");
    }
}
