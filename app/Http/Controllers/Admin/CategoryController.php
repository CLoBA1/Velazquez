<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $familyId = $request->input('family_id');

        $categories = Category::with('family')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->when($familyId, function ($query, $familyId) {
                return $query->where('family_id', $familyId);
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $families = Family::orderBy('name')->get();

        return view('admin.categories.index', compact('categories', 'search', 'families', 'familyId'));
    }

    public function create()
    {
        $families = Family::orderBy('name')->get();
        return view('admin.categories.create', compact('families'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'family_id' => ['required', 'exists:families,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $base = $data['slug'];
        $i = 2;
        while (Category::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')->with('ok', 'Categoría creada.');
    }

    public function edit(Category $category)
    {
        $families = Family::orderBy('name')->get();
        return view('admin.categories.edit', compact('category', 'families'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'family_id' => ['required', 'exists:families,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data['slug'] = Str::slug($data['name']);

        $base = $data['slug'];
        $i = 2;
        while (
            Category::where('slug', $data['slug'])
                ->where('id', '!=', $category->id)
                ->exists()
        ) {
            $data['slug'] = $base . '-' . $i;
            $i++;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')->with('ok', 'Categoría actualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('ok', 'Categoría eliminada.');
    }
}