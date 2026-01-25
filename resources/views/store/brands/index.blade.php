@extends('layouts.store')

@section('title', 'Marcas | Ferretería Velázquez')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-16 animate-fade-in-up">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-4">Nuestras Marcas</h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">Trabajamos con los mejores fabricantes para asegurar la
                calidad y durabilidad de tus proyectos.</p>
        </div>

        @if($brands->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($brands as $brand)
                    <a href="{{ route('store.brands.show', $brand->id) }}"
                        class="group bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 flex flex-col items-center justify-center gap-4 animate-fade-in-up">
                        <div
                            class="w-32 h-32 flex items-center justify-center p-4 rounded-xl border border-gray-50 bg-gray-50 group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                            @if($brand->logo_url)
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" class="w-full h-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300">
                            @else
                                <div class="text-orange-500 font-bold text-4xl">
                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-orange-600 transition-colors">
                                {{ $brand->name }}</h3>
                            <p class="text-xs text-slate-400 font-medium mt-1">{{ $brand->products_count }} productos</p>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-slate-500 text-lg">No hay marcas registradas aún.</p>
            </div>
        @endif
    </div>
@endsection