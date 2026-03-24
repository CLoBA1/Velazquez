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
        $query = Product::construction()->with(['unit', 'units.unit'])->orderBy('name');

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
            'material_type' => 'required|in:weight,piece',
            'name' => 'required|string|max:255',
            'internal_code' => 'required|string|unique:products,internal_code',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            
            // Weight validations
            'weight_per_bulto' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'price_bulto' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'price_tonelada' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'public_price' => 'nullable|numeric|min:0', // Auto-calculated price per kg
            'stock' => 'nullable|numeric|min:0',        // Total stock in kg
            
            // Piece validations
            'public_price_piece' => 'required_if:material_type,piece|nullable|numeric|min:0',
            'stock_piece' => 'nullable|integer|min:0',
        ]);

        $family = \App\Models\Family::firstOrCreate(
            ['slug' => 'construccion'],
            ['name' => 'Construcción', 'code' => 'CON']
        );
        $category_id = \App\Models\Category::firstOrCreate(
            ['slug' => 'materiales-de-construccion'],
            ['name' => 'Materiales de Construcción', 'family_id' => $family->id]
        )->id;
        $brand_id = \App\Models\Brand::firstOrCreate(
            ['slug' => 'generico'],
            ['name' => 'Genérico']
        )->id;

        // Base Data
        $data = [
            'name' => $validated['name'],
            'internal_code' => $validated['internal_code'],
            'business_line' => 'construction',
            'description' => $validated['description'] ?? 'Material de construcción',
            'category_id' => $category_id,
            'brand_id' => $brand_id,
            'slug' => Str::slug($validated['name'] . '-' . Str::random(4)),
            'taxes_percent' => 16,
            'cost_price' => 0,
            'wholesale_price' => 0,
            'mid_wholesale_price' => 0,
        ];

        if ($request->hasFile('image')) {
            $data['main_image_path'] = $request->file('image')->store('products', 'public');
        }

        if ($validated['material_type'] === 'weight') {
            // Peso Configuration (Base Unit: Kilo)
            $unitKilo = Unit::firstOrCreate(['symbol' => 'kg'], ['name' => 'Kilo']);
            $unitTonelada = Unit::firstOrCreate(['symbol' => 'ton'], ['name' => 'Tonelada']);
            $unitBulto = Unit::firstOrCreate(['symbol' => 'bulto'], ['name' => 'Bulto']);

            $data['unit_id'] = $unitKilo->id;
            $data['public_price'] = $validated['public_price']; // price per kg
            $data['sale_price'] = $validated['public_price'];
            $data['stock'] = $validated['stock'] ?? 0;

            $product = Product::create($data);

            // Create Additional Units
            // Bulto
            if ($validated['weight_per_bulto'] > 0) {
                $product->units()->create([
                    'unit_id' => $unitBulto->id,
                    'conversion_factor' => $validated['weight_per_bulto'],
                    'public_price' => $validated['price_bulto'],
                    'sale_price' => $validated['price_bulto'],
                ]);
            }
            
            // Tonelada
            $product->units()->create([
                'unit_id' => $unitTonelada->id,
                'conversion_factor' => 1000,
                'public_price' => $validated['price_tonelada'],
                'sale_price' => $validated['price_tonelada'],
            ]);

        } else {
            // Pieza Configuration (Base Unit: Pieza)
            $unitPieza = Unit::firstOrCreate(['symbol' => 'pza'], ['name' => 'Pieza']);
            
            $data['unit_id'] = $unitPieza->id;
            $data['public_price'] = $validated['public_price_piece'];
            $data['sale_price'] = $validated['public_price_piece'];
            $data['stock'] = $validated['stock_piece'] ?? 0;

            Product::create($data);
        }

        return redirect()->route('admin.construction.index')->with('success', 'Material registrado correctamente.');
    }

    public function edit(Product $product)
    {
        if ($product->business_line !== 'construction') {
            abort(404);
        }
        $product->load('units.unit');
        
        // Determine type based on base unit
        // Or if it has a 'Bulto' or 'Tonelada' unit in product_units, it's weight.
        $isWeight = $product->unit->name === 'Kilo' || $product->units->contains(fn($u) => in_array(strtolower($u->unit->name), ['bulto', 'tonelada']));
        $materialType = $isWeight ? 'weight' : 'piece';

        $weightPerBulto = 50;
        $priceBulto = 0;
        $priceTonelada = 0;

        if ($isWeight) {
            $bultoUnit = $product->units->firstWhere('unit.name', 'Bulto');
            $toneladaUnit = $product->units->firstWhere('unit.name', 'Tonelada');
            
            if ($bultoUnit) {
                $weightPerBulto = $bultoUnit->conversion_factor;
                $priceBulto = $bultoUnit->public_price;
            }
            if ($toneladaUnit) {
                $priceTonelada = $toneladaUnit->public_price;
            }
        }

        return view('admin.construction.edit', compact(
            'product', 'materialType', 'weightPerBulto', 'priceBulto', 'priceTonelada'
        ));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->business_line !== 'construction') {
            abort(404);
        }

        $validated = $request->validate([
            'material_type' => 'required|in:weight,piece',
            'name' => 'required|string|max:255',
            'internal_code' => 'required|string|unique:products,internal_code,' . $product->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            
            // Weight validations
            'weight_per_bulto' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'price_bulto' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'price_tonelada' => 'required_if:material_type,weight|nullable|numeric|min:0',
            'public_price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
            
            // Piece validations
            'public_price_piece' => 'required_if:material_type,piece|nullable|numeric|min:0',
            'stock_piece' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'internal_code' => $validated['internal_code'],
            'description' => $validated['description'] ?? $product->description,
        ];

        if ($request->hasFile('image')) {
            $data['main_image_path'] = $request->file('image')->store('products', 'public');
        }

        if ($validated['material_type'] === 'weight') {
            $unitKilo = Unit::firstOrCreate(['symbol' => 'kg'], ['name' => 'Kilo']);
            $unitTonelada = Unit::firstOrCreate(['symbol' => 'ton'], ['name' => 'Tonelada']);
            $unitBulto = Unit::firstOrCreate(['symbol' => 'bulto'], ['name' => 'Bulto']);

            $data['unit_id'] = $unitKilo->id;
            $data['public_price'] = $validated['public_price'];
            $data['sale_price'] = $validated['public_price'];
            $data['stock'] = $validated['stock'] ?? $product->stock;

            $product->update($data);

            // Reconstruct Units
            $product->units()->delete();

            if ($validated['weight_per_bulto'] > 0) {
                $product->units()->create([
                    'unit_id' => $unitBulto->id,
                    'conversion_factor' => $validated['weight_per_bulto'],
                    'public_price' => $validated['price_bulto'],
                    'sale_price' => $validated['price_bulto'],
                ]);
            }
            
            $product->units()->create([
                'unit_id' => $unitTonelada->id,
                'conversion_factor' => 1000,
                'public_price' => $validated['price_tonelada'],
                'sale_price' => $validated['price_tonelada'],
            ]);

        } else {
            $unitPieza = Unit::firstOrCreate(['symbol' => 'pza'], ['name' => 'Pieza']);
            
            $data['unit_id'] = $unitPieza->id;
            $data['public_price'] = $validated['public_price_piece'];
            $data['sale_price'] = $validated['public_price_piece'];
            $data['stock'] = $validated['stock_piece'] ?? $product->stock;

            $product->update($data);
            $product->units()->delete();
        }

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
