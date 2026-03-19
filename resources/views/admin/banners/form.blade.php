@extends('admin.layouts.app')

@section('title', isset($banner->id) ? 'Editar Banner' : 'Nuevo Banner')

@section('content')
    <div class="max-w-2xl mx-auto flex flex-col gap-8">

        <div class="flex items-center gap-4">
            <a href="{{ route('admin.banners.index') }}"
                class="text-slate-400 hover:text-slateate-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">
                    {{ isset($banner->id) ? 'Editar Banner' : 'Nuevo Banner' }}
                </h1>
                <p class="text-slate-500 mt-1">{{ isset($banner->id) ? 'Modifica los datos del banner.' : 'Crea un nuevo slide para el carrusel de la tienda.' }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($banner->id) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}"
            method="POST" enctype="multipart/form-data"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 flex flex-col gap-6">
            @csrf
            @if(isset($banner->id))
                @method('PUT')
            @endif

            {{-- Imagen --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Imagen del Banner</label>
                @if($banner->image_url)
                    <img src="{{ $banner->image_url }}" alt="Imagen actual"
                        class="w-full h-40 object-cover rounded-xl mb-3 border border-gray-100">
                @endif
                <input type="file" name="image" accept="image/*" id="banner_image"
                    class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all">
                <p class="text-xs text-slate-400 mt-1">Recomendado: 1920×700px o similar horizontal. Máx 4MB.</p>
            </div>

            {{-- Título --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Título <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $banner->title) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                    placeholder="Ej: Herramientas de Precisión" required>
            </div>

            {{-- Subtítulo (badge) --}}
            <div>
                <label for="subtitle" class="block text-sm font-semibold text-slate-700 mb-2">Subtítulo / Badge</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle', $banner->subtitle) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                    placeholder="Ej: OFERTA DEL MES">
                <p class="text-xs text-slate-400 mt-1">Aparece como pastilla pequeña arriba del título.</p>
            </div>

            {{-- Descripción --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Descripción</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition resize-none"
                    placeholder="Texto descriptivo que aparece debajo del título...">{{ old('description', $banner->description) }}</textarea>
            </div>

            {{-- Botones --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="label_primary" class="block text-sm font-semibold text-slate-700 mb-2">Texto Botón Principal</label>
                    <input type="text" name="label_primary" id="label_primary"
                        value="{{ old('label_primary', $banner->label_primary ?? 'Ver más') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                        placeholder="Ej: Comprar Ahora">
                </div>
                <div>
                    <label for="link_primary" class="block text-sm font-semibold text-slate-700 mb-2">URL Botón Principal</label>
                    <input type="text" name="link_primary" id="link_primary"
                        value="{{ old('link_primary', $banner->link_primary) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                        placeholder="/ferreteria">
                </div>
                <div>
                    <label for="label_secondary" class="block text-sm font-semibold text-slate-700 mb-2">Texto Botón Secundario</label>
                    <input type="text" name="label_secondary" id="label_secondary"
                        value="{{ old('label_secondary', $banner->label_secondary ?? 'Ver Catálogo') }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                        placeholder="Ej: Ver Catálogo">
                </div>
                <div>
                    <label for="link_secondary" class="block text-sm font-semibold text-slate-700 mb-2">URL Botón Secundario</label>
                    <input type="text" name="link_secondary" id="link_secondary"
                        value="{{ old('link_secondary', $banner->link_secondary) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition"
                        placeholder="/ofertas">
                </div>
            </div>

            {{-- Orden y Estado --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sort_order" class="block text-sm font-semibold text-slate-700 mb-2">Orden</label>
                    <input type="number" name="sort_order" id="sort_order" min="0"
                        value="{{ old('sort_order', $banner->sort_order ?? 0) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    <p class="text-xs text-slate-400 mt-1">Menor número = aparece primero.</p>
                </div>
                <div class="flex flex-col justify-center">
                    <label class="block text-sm font-semibold text-slate-700 mb-3">Estado</label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" id="is_active"
                            {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}
                            class="w-5 h-5 rounded text-indigo-600 border-gray-300 focus:ring-indigo-400">
                        <span class="text-sm text-slate-700 font-medium">Banner activo</span>
                    </label>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <a href="{{ route('admin.banners.index') }}"
                    class="text-slate-500 hover:text-slate-700 text-sm font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all">
                    {{ isset($banner->id) ? 'Guardar Cambios' : 'Crear Banner' }}
                </button>
            </div>
        </form>
    </div>
@endsection
