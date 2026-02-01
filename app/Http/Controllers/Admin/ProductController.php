<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::query()
            ->with(['category.family', 'brand', 'unit'])
            ->orderByDesc('id');

        // Filtros
        $familyId = $request->integer('family_id');
        $categoryId = $request->integer('category_id');
        $brandId = $request->integer('brand_id');
        $search = trim((string) $request->get('search', ''));

        if ($familyId) {
            $q->whereHas('category', fn($qq) => $qq->where('family_id', $familyId));
        }

        if ($categoryId) {
            $q->where('category_id', $categoryId);
        }

        if ($brandId) {
            $q->where('brand_id', $brandId);
        }

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('internal_code', 'like', "%{$search}%")
                    ->orWhere('supplier_sku', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $products = $q->paginate(20)->withQueryString();

        // Para selects
        $families = Family::orderBy('name')->get();
        $categories = Category::with('family')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'families', 'categories', 'brands'));
    }

    public function create()
    {
        $families = Family::orderBy('name')->get();
        $categories = Category::with('family')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('admin.products.create', compact('families', 'categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        // 1. Validar datos comunes
        $data = $request->validate([
            'business_line' => ['required', 'in:hardware,construction'],
            'internal_code' => ['required', 'string', 'max:255', 'unique:products,internal_code'],
            'supplier_sku' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => [
                Rule::requiredIf($request->business_line === 'hardware'),
                'nullable',
                'exists:brands,id'
            ],
            'unit_id' => ['required', 'exists:units,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'main_image' => ['nullable', 'image', 'max:4096'],
        ]);

        // 2. Precios: Solo Admin puede setearlos. Si no, default 0
        if ($request->user()->isAdmin()) {
            $prices = $request->validate([
                'cost_price' => ['required', 'numeric', 'min:0'],
                'taxes_percent' => ['nullable', 'numeric', 'min:0'],
                'sale_price' => ['required', 'numeric', 'min:0'],
                'public_price' => ['required', 'numeric', 'min:0'],
                'mid_wholesale_price' => ['required', 'numeric', 'min:0'],
                'wholesale_price' => ['required', 'numeric', 'min:0'],
            ]);
            // If taxes_percent is null (empty), default to 0
            $prices['taxes_percent'] = $prices['taxes_percent'] ?? 0;
            $data = array_merge($data, $prices);
        } else {
            // Staff / Otro: Se crea con precios en 0
            $data['cost_price'] = 0;
            $data['sale_price'] = 0;
            $data['public_price'] = 0;
            $data['mid_wholesale_price'] = 0;
            $data['wholesale_price'] = 0;
        }

        $data['slug'] = Str::slug($data['name'] . ' ' . $data['internal_code']);
        $base = $data['slug'];
        $i = 2;
        while (Product::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        if ($request->hasFile('main_image')) {
            $data['main_image_path'] = $request->file('main_image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('ok', 'Producto creado.');
    }

    public function edit(Product $product)
    {
        $families = Family::orderBy('name')->get();
        $categories = Category::with('family')->orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'families', 'categories', 'brands', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        // 1. Validar comunes
        // 1. Validar comunes
        $data = $request->validate([
            'business_line' => ['required', 'in:hardware,construction'],
            'internal_code' => ['required', 'string', 'max:255', 'unique:products,internal_code,' . $product->id],
            'supplier_sku' => ['nullable', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => [
                Rule::requiredIf($request->business_line === 'hardware'),
                'nullable',
                'exists:brands,id'
            ],
            'unit_id' => ['required', 'exists:units,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'main_image' => ['nullable', 'image', 'max:4096'],
            'remove_image' => ['nullable'],
        ]);

        // 2. Precios: Solo Admin puede actualizarlos
        if ($request->user()->isAdmin()) {
            $prices = $request->validate([
                'cost_price' => ['required', 'numeric', 'min:0'],
                'taxes_percent' => ['nullable', 'numeric', 'min:0'],
                'sale_price' => ['required', 'numeric', 'min:0'],
                'public_price' => ['required', 'numeric', 'min:0'],
                'mid_wholesale_price' => ['required', 'numeric', 'min:0'],
                'wholesale_price' => ['required', 'numeric', 'min:0'],
            ]);
            // If taxes_percent is null (empty), default to 0
            $prices['taxes_percent'] = $prices['taxes_percent'] ?? 0;
            $data = array_merge($data, $prices);
        }
        // Si no es admin, no tocamos los precios (no se agregan a $data, por tanto no se actualizan)

        $data['slug'] = Str::slug($data['name'] . ' ' . $data['internal_code']);
        $base = $data['slug'];
        $i = 2;
        while (
            Product::where('slug', $data['slug'])
                ->where('id', '!=', $product->id)
                ->exists()
        ) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        if (($data['remove_image'] ?? null) && $product->main_image_path) {
            $data['main_image_path'] = null;
        }

        if ($request->hasFile('main_image')) {
            $data['main_image_path'] = $request->file('main_image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('ok', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        // Opcional: Solo admin puede borrar
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Solo administradores pueden eliminar productos.');
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('ok', 'Producto eliminado.');
    }
}