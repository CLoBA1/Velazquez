@extends('layouts.store')

@section('title', $product->name)

@section('content')
    <div class="bg-white min-h-screen pb-20" x-data="{ 
                                                activeImage: '{{ $product->image_url }}', 
                                                qty: 1,
                                                zoom: false
                                            }">

        <!-- Breadcrumb (Modern) -->
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-400">
                @if($product->business_line === 'construction')
                    <li><a href="{{ route('construction.index') }}" class="hover:text-slate-900 transition-colors">Inicio</a>
                    </li>
                @else
                    <li><a href="{{ route('store.index') }}" class="hover:text-slate-900 transition-colors">Inicio</a></li>
                @endif
                <li><svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg></li>
                @if($product->category && $product->business_line !== 'construction')
                    <li>
                        <a href="{{ route('store.index', ['category' => $product->category_id]) }}"
                            class="hover:text-slate-900 transition-colors">{{ $product->category->name }}</a>
                    </li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg></li>
                @endif
                <li class="font-medium text-slate-900 truncate max-w-[200px]">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 xl:gap-20">

                <!-- Gallery Section (Left) -->
                <div class="space-y-6">
                    <!-- Main Image Stage (Constrained Height) -->
                    <div class="aspect-square max-h-[500px] w-full mx-auto bg-gray-50 rounded-3xl overflow-hidden relative group border border-gray-100 flex items-center justify-center p-8 bg-white cursor-zoom-in"
                        @click="zoom = !zoom">

                        <template x-if="!activeImage">
                            <div class="text-gray-300 flex flex-col items-center">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-sm font-medium text-gray-400">Sin imagen disponible</span>
                            </div>
                        </template>
                        <template x-if="activeImage">
                            <img :src="activeImage"
                                class="max-w-full max-h-full object-contain transition-transform duration-500"
                                :class="{'scale-150': zoom}">
                        </template>

                        <!-- Zoom Hint -->
                        <div
                            class="absolute top-4 right-4 bg-white/80 backdrop-blur rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none text-slate-500 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Thumbnails (Only Real Images) -->
                    @if($product->image_url)
                        <div class="flex gap-4 justify-center md:justify-start">
                            <!-- Main Image Thumb -->
                            <button @click="activeImage = '{{ $product->image_url }}'"
                                class="w-20 h-20 rounded-xl border-2 transition-all p-2 bg-white flex items-center justify-center"
                                :class="activeImage === '{{ $product->image_url }}' ? 'border-primary ring-2 ring-blue-100' : 'border-gray-100 hover:border-gray-300'">
                                <img src="{{ $product->image_url }}" class="w-full h-full object-contain pointer-events-none">
                            </button>

                            <!-- Here we could verify if product has 'images' relationship (gallery) and loop them -->
                            <!-- Since current model only shows image_url, we only show one thumb or placeholder -->
                        </div>
                    @endif
                </div>

                <!-- Product Info Section (Right - Sticky) -->
                <div class="lg:sticky lg:top-24 h-fit space-y-8">

                    <!-- Header -->
                    <div>
                        <h2
                            class="text-sm font-bold {{ $product->business_line === 'construction' ? 'text-blue-600' : 'text-secondary' }} uppercase tracking-widest mb-2">
                            {{ $product->business_line === 'construction' ? 'MATERIALES DE CONSTRUCCIÓN' : ($product->brand ? $product->brand->name : 'FERRETERÍA PREMIUM') }}
                        </h2>
                        <h1 class="text-4xl sm:text-5xl font-extrabold text-dark tracking-tight leading-tight mb-4">
                            {{ $product->name }}
                        </h1>

                        <div class="flex items-center gap-4">
                            <div class="flex text-yellow-400">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                                <svg class="w-5 h-5 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-slate-500"><span
                                    class="text-slate-900 border-b border-gray-300">4.0</span> (12 reseñas)</span>
                        </div>
                    </div>

                    <!-- Price & Actions Component -->
                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                        <livewire:store.product-page-actions :product="$product" />
                    </div>

                    <!-- Details & Specs -->
                    <div class="space-y-6">
                        <p class="text-slate-600 leading-relaxed text-lg">
                            {{ $product->description ?? 'Este producto es de alta calidad y está diseñado para durar. Ideal para uso profesional o doméstico exigente.' }}
                        </p>

                        <!-- Specs Grid -->
                        <div class="border-t border-gray-100 pt-6">
                            <h3 class="font-bold text-slate-900 mb-4">Especificaciones Técnicas</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <span class="block text-gray-500 mb-1">Modelo</span>
                                    <span class="font-semibold text-slate-900">{{ $product->code ?? 'N/A' }}</span>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <span class="block text-gray-500 mb-1">Unidad</span>
                                    <span class="font-semibold text-slate-900">{{ $product->unit->name ?? 'Pieza' }}</span>
                                </div>
                                @if($product->brand)
                                    <div class="p-4 bg-gray-50 rounded-lg">
                                        <span class="block text-gray-500 mb-1">Garantía</span>
                                        <span class="font-semibold text-slate-900">Directa de Marca</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection