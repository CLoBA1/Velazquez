@extends('layouts.store')

@section('title', 'Detalle de Renta | Ferretería Velázquez')

@section('content')
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">

                @if(session('success'))
                    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mb-8">
                    <a href="{{ route('rentals.index') }}"
                        class="text-sm font-medium text-slate-500 hover:text-slate-700 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Volver a mis rentas
                    </a>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div
                        class="px-6 py-8 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-slate-900">Renta
                                #{{ str_pad($rental->id, 6, '0', STR_PAD_LEFT) }}</h1>
                            <p class="text-slate-500 text-sm mt-1">Solicitada el {{ $rental->created_at->format('d F Y') }}
                            </p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold tracking-wide uppercase
                                {{ $rental->status === 'returned' ? 'bg-green-100 text-green-800' :
        ($rental->status === 'active' ? 'bg-blue-100 text-blue-800' :
            ($rental->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ $rental->status }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="w-full md:w-1/3">
                                <div class="aspect-w-16 aspect-h-12 bg-gray-100 rounded-xl overflow-hidden mb-4">
                                    @if($rental->machine->image_url)
                                        <img src="{{ $rental->machine->image_url }}" alt="{{ $rental->machine->name }}"
                                            class="object-cover w-full h-full">
                                    @else
                                        <div class="flex items-center justify-center h-48 bg-gray-100 text-gray-400">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 space-y-6">
                                <div>
                                    <h2 class="text-xl font-bold text-slate-900">{{ $rental->machine->name }}</h2>
                                    <p class="text-slate-500">{{ $rental->machine->brand }} - {{ $rental->machine->model }}
                                    </p>
                                </div>

                                <div
                                    class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Fecha
                                            Inicio</p>
                                        <p class="font-bold text-slate-900">{{ $rental->start_date->format('d M Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">Fecha
                                            Fin</p>
                                        <p class="font-bold text-slate-900">{{ $rental->end_date->format('d M Y') }}</p>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end border-t border-gray-100 pt-4">
                                    <div>
                                        <p class="text-sm text-slate-500">Costo por día</p>
                                        <p class="font-medium text-slate-900">
                                            ${{ number_format($rental->machine->price_per_day, 2) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-slate-500">Total Estimado</p>
                                        <p class="text-2xl font-black text-indigo-600">
                                            ${{ number_format($rental->total_cost, 2) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection