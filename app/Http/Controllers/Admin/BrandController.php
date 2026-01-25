<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(20);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $base = $data['slug'];
        $i = 2;
        while (Brand::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('ok', 'Marca creada.');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $request->file('logo')->store('brands', 'public');
        }

        $base = $data['slug'];
        $i = 2;
        while (
            Brand::where('slug', $data['slug'])
                ->where('id', '!=', $brand->id)
                ->exists()
        ) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('ok', 'Marca actualizada.');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('ok', 'Marca eliminada.');
    }
}