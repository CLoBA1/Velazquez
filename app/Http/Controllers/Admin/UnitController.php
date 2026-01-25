<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::orderBy('name')->paginate(20);
        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        return view('admin.units.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:20'],
            'allows_decimal' => ['nullable'],
        ]);

        $data['symbol'] = trim($data['symbol']);
        $data['allows_decimal'] = (bool)($data['allows_decimal'] ?? false);

        $data['slug'] = Str::slug($data['name'].' '.$data['symbol']);

        $base = $data['slug'];
        $i = 2;
        while (Unit::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base.'-'.$i;
            $i++;
        }

        Unit::create($data);

        return redirect()->route('admin.units.index')->with('ok', 'Unidad creada.');
    }

    public function edit(Unit $unit)
    {
        return view('admin.units.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'symbol' => ['required', 'string', 'max:20'],
            'allows_decimal' => ['nullable'],
        ]);

        $data['symbol'] = trim($data['symbol']);
        $data['allows_decimal'] = (bool)($data['allows_decimal'] ?? false);

        $data['slug'] = Str::slug($data['name'].' '.$data['symbol']);

        $base = $data['slug'];
        $i = 2;
        while (
            Unit::where('slug', $data['slug'])
                ->where('id', '!=', $unit->id)
                ->exists()
        ) {
            $data['slug'] = $base.'-'.$i;
            $i++;
        }

        $unit->update($data);

        return redirect()->route('admin.units.index')->with('ok', 'Unidad actualizada.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('admin.units.index')->with('ok', 'Unidad eliminada.');
    }
}