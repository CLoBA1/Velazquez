@extends('admin.layouts.app')

@section('title', 'Banners del Carrusel')

@section('content')
    <div class="flex flex-col gap-8">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Banners del Carrusel</h1>
                <p class="text-slate-500 mt-1">Gestiona las imágenes y slides del carrusel principal de la tienda.</p>
            </div>
            <a href="{{ route('admin.banners.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Banner
            </a>
        </div>

        @if(session('ok'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-5 py-3 text-sm font-medium">
                ✅ {{ session('ok') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @if($banners->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="font-semibold text-lg text-slate-400">No hay banners creados aún.</p>
                    <p class="text-sm mt-1">Crea el primer banner para que aparezca en el carrusel de la tienda.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-4 font-semibold text-slate-500">Imagen</th>
                            <th class="text-left px-6 py-4 font-semibold text-slate-500">Título</th>
                            <th class="text-left px-6 py-4 font-semibold text-slate-500">Subtítulo</th>
                            <th class="text-center px-6 py-4 font-semibold text-slate-500">Orden</th>
                            <th class="text-center px-6 py-4 font-semibold text-slate-500">Estado</th>
                            <th class="text-right px-6 py-4 font-semibold text-slate-500">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($banners as $banner)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    @if($banner->image_url)
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                            class="w-20 h-12 object-cover rounded-lg border border-gray-100">
                                    @else
                                        <div class="w-20 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $banner->title }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $banner->subtitle ?? '—' }}</td>
                                <td class="px-6 py-4 text-center text-slate-600">{{ $banner->sort_order }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($banner->is_active)
                                        <span class="inline-block bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full">Activo</span>
                                    @else
                                        <span class="inline-block bg-gray-100 text-gray-500 text-xs font-bold px-3 py-1 rounded-full">Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.banners.edit', $banner) }}"
                                            class="text-indigo-600 hover:text-indigo-800 font-semibold text-xs px-3 py-1.5 rounded-lg hover:bg-indigo-50 transition-colors">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST"
                                            onsubmit="return confirm('¿Eliminar este banner?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-semibold text-xs px-3 py-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
