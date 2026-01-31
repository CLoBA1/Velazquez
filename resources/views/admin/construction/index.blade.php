@extends('admin.layouts.app')

@section('title', 'Materiales de Construcción')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Materiales de Construcción</h1>
            <p class="text-slate-500 mt-1">Gestión simplificada de inventario para obra.</p>
        </div>
        <a href="{{ route('admin.construction.create') }}"
            class="inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-5 py-3 rounded-xl font-bold shadow-lg shadow-orange-900/20 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nuevo Material
        </a>
    </div>

    <!-- Buscador -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
        <form action="{{ route('admin.construction.index') }}" method="GET" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre o código..."
                class="flex-1 rounded-lg border-slate-200 focus:ring-orange-500 focus:border-orange-500">
            <button type="submit"
                class="px-6 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-lg transition-colors">
                Buscar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-4">Material</th>
                        <th class="px-6 py-4">Código</th>
                        <th class="px-6 py-4">Precio Público</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900 flex items-center gap-3">
                                @if($product->main_image_path)
                                    <img src="{{ asset('storage/' . $product->main_image_path) }}"
                                        class="w-10 h-10 rounded-lg object-cover bg-slate-100">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                @endif
                                {{ $product->name }}
                            </td>
                            <td class="px-6 py-4 font-mono text-xs">{{ $product->internal_code }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">${{ number_format($product->public_price, 2) }} /
                                {{ $product->unit->symbol ?? 'pz' }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 10 ? 'bg-green-100 text-green-800' : ($product->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                                <a href="{{ route('admin.construction.edit', $product) }}"
                                    class="text-blue-600 hover:text-blue-900 font-medium">Editar</a>
                                <form action="{{ route('admin.construction.destroy', $product) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar material?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                No hay materiales registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>
@endsection