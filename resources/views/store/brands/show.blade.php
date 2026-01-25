@extends('layouts.store')

@section('title', $brand->name . ' | Ferretería Velázquez')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-12 animate-fade-in-up">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 bg-white rounded-2xl flex items-center justify-center border border-gray-100 shadow-lg shadow-gray-200/50 p-4">
                 @if($brand->logo_url)
                    <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain">
                @else
                    <span class="text-orange-500 font-black text-4xl">{{ strtoupper(substr($brand->name, 0, 1)) }}</span>
                @endif
            </div>
            <div>
                 <span class="text-slate-400 text-sm font-bold uppercase tracking-wider">Marca</span>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">{{ $brand->name }}</h1>
            </div>
        </div>
        <a href="{{ route('store.brands.index') }}" class="text-slate-500 hover:text-slate-900 font-bold text-sm flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver a Marcas
        </a>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8 animate-fade-in-up delay-100">
             @foreach($products as $product)
                 <div class="group bg-white rounded-[2rem] p-4 transition-all duration-500 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 border border-transparent hover:border-orange-100 flex flex-col relative h-full">
                        
                    <!-- Badges -->
                    <div class="absolute top-6 left-6 z-20 flex flex-col gap-2 pointer-events-none">
                        @if($product->stock <= 0)
                                <span class="bg-slate-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-slate-900/30 uppercase tracking-wider backdrop-blur-md">Agotado</span>
                        @endif
                         @if($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                <span class="bg-orange-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-orange-500/30 uppercase tracking-wider backdrop-blur-md">Oferta</span>
                        @endif
                    </div>

                    <!-- Image -->
                    <a href="{{ route('store.show', $product->id) }}" class="block relative aspect-[4/5] bg-gray-50 rounded-[1.5rem] overflow-hidden mb-6 flex items-center justify-center p-8 transition-colors group-hover:bg-gray-50/50">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                            @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="relative z-10 w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700 ease-out">
                        @else
                            <div class="relative z-10 text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </a>

                    <!-- Info -->
                    <div class="flex-1 flex flex-col px-2 pb-2">
                        <h3 class="font-bold text-lg text-slate-800 leading-snug mb-3 group-hover:text-orange-600 transition-colors duration-300 line-clamp-2 min-h-[3.25rem]">
                            <a href="{{ route('store.show', $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h3>
                        
                        <div class="mt-auto flex items-center justify-between">
                            <div class="flex flex-col">
                                @if($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                    <span class="text-xs font-semibold text-gray-400 line-through mb-0.5">${{ number_format($product->public_price * 1.1, 0) }}</span>
                                    <span class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                @else
                                    <span class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                @endif
                            </div>
                            
                           @if($product->stock > 0)
                                <livewire:store.add-to-cart :productId="$product->id" :key="$product->id" />
                            @else
                                <button disabled class="bg-slate-200 text-slate-400 w-12 h-12 rounded-full flex items-center justify-center cursor-not-allowed" title="Agotado">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200 animate-fade-in-up">
            <h2 class="text-2xl font-bold text-slate-900 mb-2">No hay productos disponibles</h2>
            <p class="text-slate-500">Esta marca no tiene productos en stock actualmente.</p>
        </div>
    @endif

</div>
@endsection
