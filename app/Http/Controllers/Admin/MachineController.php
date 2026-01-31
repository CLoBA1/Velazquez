<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MachineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $machines = \App\Models\Machine::orderBy('name')->paginate(10);
        return view('admin.machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.machines.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_code' => 'nullable|string|max:255|unique:machines',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,reserved',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('machines', 'public');
            $validated['main_image_path'] = $path;
        }

        \App\Models\Machine::create($validated);

        return redirect()->route('admin.machines.index')->with('success', 'Maquinaria creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not used in admin for now
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Machine $machine)
    {
        return view('admin.machines.edit', compact('machine'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_code' => 'nullable|string|max:255|unique:machines,internal_code,' . $machine->id,
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'price_per_day' => 'nullable|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'status' => 'required|in:available,rented,maintenance,reserved',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('machines', 'public');
            $validated['main_image_path'] = $path;
        }

        // Only update slug if name changed? Or keep permanent? Let's update for now or Str::slug is idempotent
        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);

        $machine->update($validated);

        return redirect()->route('admin.machines.index')->with('success', 'Maquinaria actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Machine $machine)
    {
        $machine->delete();
        return redirect()->route('admin.machines.index')->with('success', 'Maquinaria eliminada correctamente.');
    }
}
