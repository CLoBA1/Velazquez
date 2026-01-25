<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Family::orderBy('name')->paginate(20);
        return view('admin.families.index', compact('families'));
    }

    public function create()
    {
        return view('admin.families.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:families,code'],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['slug'] = Str::slug($data['name']);

        // garantizar slug único
        $base = $data['slug'];
        $i = 2;
        while (Family::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base.'-'.$i;
            $i++;
        }

        Family::create($data);

        return redirect()->route('admin.families.index')->with('ok', 'Familia creada.');
    }

    public function edit(Family $family)
    {
        return view('admin.families.edit', compact('family'));
    }

    public function update(Request $request, Family $family)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:20', 'unique:families,code,'.$family->id],
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['slug'] = Str::slug($data['name']);

        // garantizar slug único (excluyendo el actual)
        $base = $data['slug'];
        $i = 2;
        while (
            Family::where('slug', $data['slug'])
                ->where('id', '!=', $family->id)
                ->exists()
        ) {
            $data['slug'] = $base.'-'.$i;
            $i++;
        }

        $family->update($data);

        return redirect()->route('admin.families.index')->with('ok', 'Familia actualizada.');
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return redirect()->route('admin.families.index')->with('ok', 'Familia eliminada.');
    }
}