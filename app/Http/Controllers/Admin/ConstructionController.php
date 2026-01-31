<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConstructionController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::construction()->orderBy('name');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('internal_code', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate(20);

        return view('admin.construction.index', compact('products'));
    }

    public function create()
    {
        // For construction, we might want specific units like Ton, Bulk, etc.
        // For now get all or filter if needed.
        $units = Unit::orderBy('name')->get();

        return view('admin.construction.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_code' => 'required|string|unique:products,internal_code',
            'unit_id' => 'required|exists:units,id',
            'cost_price' => 'nullable|numeric|min:0',
            'public_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        // Default values for fields not present in simplified form
        $data = [
            'name' => $validated['name'],
            'internal_code' => $validated['internal_code'],
            'unit_id' => $validated['unit_id'],
            'business_line' => 'construction',
            'description' => $validated['description'] ?? 'Material de construcción',
            'cost_price' => $validated['cost_price'] ?? 0,
            'public_price' => $validated['public_price'],
            'sale_price' => $validated['public_price'], // Base sale price same as public for now
            'wholesale_price' => $validated['wholesale_price'] ?? 0,
            'mid_wholesale_price' => $validated['wholesale_price'] ?? 0,
            'stock' => $validated['stock'] ?? 0,
            // Defaults
            // Defaults
            'category_id' => \App\Models\Category::where('name', 'Materiales de Construcción')->first()?->id ?? \App\Models\Category::first()?->id,
            'brand_id' => \App\Models\Brand::where('name', 'Genérico')->first()?->id ?? \App\Models\Brand::first()?->id,
            'slug' => Str::slug($validated['name'] . '-' . Str::random(4)),
            'taxes_percent' => 16, // Default TAX
        ];

        if ($request->hasFile('image')) {
            $data['main_image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.construction.index')->with('success', 'Material registrado correctamente.');
    }

    public function edit(Product $product)
    {
        if ($product->business_line !== 'construction') {
            abort(404);
        }
        $units = Unit::orderBy('name')->get();
        return view('admin.construction.edit', compact('product', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->business_line !== 'construction') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_code' => 'required|string|unique:products,internal_code,' . $product->id,
            'unit_id' => 'required|exists:units,id',
            'public_price' => 'required|numeric|min:0',
            'wholesale_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $validated['name'],
            'internal_code' => $validated['internal_code'],
            'unit_id' => $validated['unit_id'],
            'public_price' => $validated['public_price'],
            'sale_price' => $validated['public_price'],
            'wholesale_price' => $validated['wholesale_price'] ?? 0,
            'stock' => $validated['stock'] ?? $product->stock,
            'description' => $validated['description'] ?? $product->description,
        ];

        if ($request->hasFile('image')) {
            $data['main_image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.construction.index')->with('success', 'Material actualizado.');
    }

    public function destroy(Product $product)
    {
        if ($product->business_line !== 'construction') {
            abort(404);
        }
        $product->delete();
        return redirect()->route('admin.construction.index')->with('success', 'Material eliminado.');
    }
}
