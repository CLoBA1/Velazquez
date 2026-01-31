@extends('layouts.store')

@section('content')
    <div class="bg-stone-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-stone-900 sm:text-4xl">
                    Materiales de Construcción
                </h2>
                <p class="mt-4 text-lg text-stone-500">
                    Soluciones integrales para obra negra, gris y acabados. Venta por volumen.
                </p>
            </div>

            <div class="mt-12">
                @if($products->count() > 0)
                    <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-8">
                        @foreach($products as $product)
                            <div
                                class="group relative bg-white border border-stone-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                <div
                                    class="aspect-w-1 aspect-h-1 bg-stone-200 group-hover:opacity-75 h-64 w-full overflow-hidden xl:aspect-w-7 xl:aspect-h-8">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                            class="w-full h-full object-center object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-stone-100 text-stone-300">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                            </svg>
                                        </div>
                                    @endif
                                    @if($product->stock <= 0)
                                        <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold text-white bg-red-500 rounded">
                                            AGOTADO
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 p-4 space-y-2 flex flex-col">
                                    <h3 class="text-lg font-bold text-stone-900">
                                        <a href="{{ route('store.show', $product) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-stone-500">{{ $product->brand->name ?? '' }} ·
                                        {{ $product->unit->name ?? 'Unidad' }}</p>
                                    <p class="text-sm text-stone-600 flex-1">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span
                                            class="text-xl font-extrabold text-stone-900">${{ number_format($product->public_price, 2) }}</span>
                                        @if($product->wholesale_price > 0)
                                            <div class="text-right">
                                                <p class="text-xs text-stone-500">Mayoreo desde</p>
                                                <p class="text-sm font-bold text-stone-700">
                                                    ${{ number_format($product->wholesale_price, 2) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-4 bg-stone-50 border-t border-stone-100 flex items-center justify-center">
                                    <span class="text-stone-600 font-semibold text-sm group-hover:text-stone-900">Ver
                                        Detalles</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-24">
                        <svg class="mx-auto h-12 w-12 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-stone-900">No hay materiales disponibles por el momento</h3>
                        <p class="mt-1 text-sm text-stone-500">Estamos actualizando nuestro inventario de construcción.</p>
                    </div>
                @endif

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection