@extends('layouts.store')

@section('title', '¡Pedido Recibido! | Ferretería Velázquez')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="max-w-xl mx-auto text-center">

            <div
                class="bg-green-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-8 animate-fade-in-up">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>

            <h1 class="text-4xl font-black text-dark mb-4 tracking-tight animate-fade-in-up delay-100">¡Gracias por tu
                Compra!</h1>
            <p class="text-lg text-slate-600 mb-8 animate-fade-in-up delay-200">Tu pedido ha sido registrado exitosamente.
                Nos pondremos en contacto contigo pronto para coordinar la entrega.</p>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm mb-10 animate-fade-in-up delay-300">
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-1">Número de Pedido</p>
                <p class="text-3xl font-black text-dark font-mono">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            <div class="animate-fade-in-up delay-300">
                <a href="{{ route('store.index') }}"
                    class="inline-flex items-center gap-2 bg-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-blue-700 transition-colors shadow-xl shadow-blue-500/20">
                    Volver a la Tienda
                </a>
            </div>

        </div>
    </div>
@endsection