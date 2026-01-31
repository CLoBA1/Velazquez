@extends('layouts.store')

@section('title', 'Mis Compras | Ferretería Velázquez')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-black text-slate-900 mb-8">Mis Compras</h1>

            @if($sales->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <ul class="divide-y divide-gray-100">
                        @foreach($sales as $sale)
                            <li class="hover:bg-slate-50 transition-colors">
                                <a href="{{ route('sales.show', $sale) }}" class="block p-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div class="bg-blue-50 text-blue-600 rounded-full p-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-lg font-bold text-slate-900">Pedido #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</p>
                                                <p class="text-sm text-slate-500">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-slate-900">${{ number_format($sale->total, 2) }}</p>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $sale->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                   ($sale->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                {{ ucfirst($sale->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-4 flex items-center gap-2 text-sm text-slate-500">
                                        <span>{{ $sale->items->count() }} productos</span>
                                        <span>&bull;</span>
                                        <span>Ver detalles <span aria-hidden="true">&rarr;</span></span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8">
                    {{ $sales->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900">Aún no tienes compras</h3>
                    <p class="mt-1 text-slate-500">Explora nuestro catálogo y encuentra lo que necesitas.</p>
                    <div class="mt-6">
                        <a href="{{ route('store.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Ir a la Tienda
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
