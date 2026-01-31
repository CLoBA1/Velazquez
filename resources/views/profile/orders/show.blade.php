@extends('layouts.store')

@section('title', 'Detalle de Compra | Ferretería Velázquez')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <div class="mb-8">
                    <a href="{{ route('sales.my-purchases') }}"
                        class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a mis compras
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div
                        class="px-6 py-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900">Pedido
                                #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</h1>
                            <p class="text-slate-500 text-sm mt-1">Realizado el {{ $sale->created_at->format('d F Y') }} a
                                las {{ $sale->created_at->format('h:i A') }}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold tracking-wide uppercase
                                {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' :
        ($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ $sale->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-lg font-bold text-slate-800 mb-6">Productos</h2>
                        <ul class="divide-y divide-gray-100">
                            @foreach($sale->items as $item)
                                <li class="py-6 flex items-start gap-4">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-lg flex-shrink-0 overflow-hidden border border-gray-200">
                                        @if($item->product->main_image_path)
                                            <img src="{{ asset('storage/' . $item->product->main_image_path) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-base font-bold text-slate-900">{{ $item->product->name }}</h3>
                                        <p class="text-sm text-slate-500 mb-2">
                                            {{ $item->product->brand->name ?? 'Marca Genérica' }}</p>
                                        <div class="flex justify-between items-end">
                                            <div class="text-sm text-slate-600">
                                                {{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}
                                            </div>
                                            <div class="text-base font-bold text-slate-900">
                                                ${{ number_format($item->total, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-gray-50 px-6 py-6 border-t border-gray-100">
                        <div class="flex flex-col gap-2 md:w-1/3 md:ml-auto">
                            <div
                                class="flex justify-between text-base font-bold text-slate-900 pt-2 border-t border-gray-200">
                                <span>Total</span>
                                <span>${{ number_format($sale->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection