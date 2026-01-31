@extends('admin.layouts.app')

@section('title', 'Editar Material')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Editar Material</h1>
                <p class="text-slate-500 mt-1">Actualizando: {{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.construction.index') }}"
                class="px-4 py-2 bg-white border rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">Cancelar</a>
        </div>

        <form action="{{ route('admin.construction.update', $product) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre del Material</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Código Interno / SKU</label>
                    <input type="text" name="internal_code" value="{{ old('internal_code', $product->internal_code) }}"
                        required class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500"
                        readonly>
                    <p class="text-xs text-slate-400 mt-1">No editable</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Unidad de Venta</label>
                    <select name="unit_id" required
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" @selected($product->unit_id == $unit->id)>{{ $unit->name }}
                                ({{ $unit->symbol }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Descripción (Opcional)</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Precio Público</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                        <input type="number" step="0.50" name="public_price"
                            value="{{ old('public_price', $product->public_price) }}" required
                            class="pl-7 w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500 font-bold text-lg">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Precio Mayoreo (Opcional)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                        <input type="number" step="0.50" name="wholesale_price"
                            value="{{ old('wholesale_price', $product->wholesale_price) }}"
                            class="pl-7 w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Stock Actual</label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}"
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <label class="block text-sm font-bold text-slate-700 mb-2">Imagen del Material</label>
                @if($product->main_image_path)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $product->main_image_path) }}"
                            class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                @endif
                <input type="file" name="image"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <button type="submit"
                class="w-full py-4 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-xl shadow-lg shadow-orange-900/20 transition-all">
                Actualizar Material
            </button>
        </form>
    </div>
@endsection