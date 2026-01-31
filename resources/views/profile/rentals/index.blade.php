@extends('layouts.store')

@section('title', 'Mis Rentas | Ferretería Velázquez')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl font-black text-slate-900 mb-8">Mis Rentas</h1>

                @if($rentals->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                        <ul class="divide-y divide-gray-100">
                            @foreach($rentals as $rental)
                                        <li class="hover:bg-slate-50 transition-colors">
                                            <a href="{{ route('rentals.show', $rental) }}" class="block p-6">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-4">
                                                        <div class="bg-yellow-50 text-yellow-600 rounded-full p-3">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <p class="text-lg font-bold text-slate-900">Renta
                                                                #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</p>
                                                            <p class="text-sm text-slate-500">{{ $rental->machine->name }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-lg font-bold text-slate-900">
                                                            ${{ number_format($rental->total_cost, 2) }}</p>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                                    {{ $rental->status === 'returned' ? 'bg-green-100 text-green-800' :
                                ($rental->status === 'active' ? 'bg-blue-100 text-blue-800' :
                                    ($rental->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                                            {{ ucfirst($rental->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="mt-4 flex items-center gap-2 text-sm text-slate-500">
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Del: {{ $rental->start_date->format('d M') }}
                                                    </div>
                                                    <div class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Al: {{ $rental->end_date->format('d M') }}
                                                    </div>
                                                    <span class="ml-auto flex items-center">Ver detalles <span
                                                            aria-hidden="true">&rarr;</span></span>
                                                </div>
                                            </a>
                                        </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-8">
                        {{ $rentals->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-900">Aún no tienes rentas</h3>
                        <p class="mt-1 text-slate-500">Explora nuestro catálogo de maquinaria.</p>
                        <div class="mt-6">
                            <a href="{{ route('machinery.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                                Ir a Maquinaria
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection