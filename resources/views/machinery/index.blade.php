@extends('layouts.store')

@section('content')
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Renta de Maquinaria
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    Equipos de alto rendimiento para tus proyectos de construcción.
                </p>
            </div>

            <div class="mt-12 grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse($machines as $machine)
                    <div
                        class="group relative bg-white border border-gray-200 rounded-lg flex flex-col overflow-hidden hover:shadow-lg transition-shadow duration-300">
                        <div class="aspect-w-3 aspect-h-4 bg-gray-200 group-hover:opacity-75 sm:aspect-none sm:h-56">
                            @if($machine->image_url)
                                <img src="{{ $machine->image_url }}" alt="{{ $machine->name }}"
                                    class="w-full h-full object-center object-cover sm:h-full sm:w-full">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            @endif
                            @if($machine->status !== 'available')
                                <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold text-white bg-red-500 rounded">
                                    {{ strtoupper($machine->status) }}
                                </div>
                            @else
                                <div class="absolute top-2 right-2 px-2 py-1 text-xs font-bold text-white bg-green-500 rounded">
                                    DISPONIBLE
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 p-4 space-y-2 flex flex-col">
                            <h3 class="text-sm font-medium text-gray-900">
                                <a href="{{ route('machinery.show', $machine) }}">
                                    <span aria-hidden="true" class="absolute inset-0"></span>
                                    {{ $machine->name }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500">{{ $machine->brand }} {{ $machine->model }}</p>
                            <p class="text-sm text-gray-500 flex-1">{{ Str::limit($machine->description, 60) }}</p>
                            <div class="flex flex-col mt-4">
                                @if($machine->price_per_day)
                                    <p class="text-lg font-bold text-gray-900">
                                        ${{ number_format($machine->price_per_day, 2) }} <span
                                            class="text-sm font-normal text-gray-500">/ día</span>
                                    </p>
                                @endif
                                @if($machine->price_per_hour)
                                    <p class="text-sm text-gray-600">
                                        ${{ number_format($machine->price_per_hour, 2) }} / hora
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            <span class="block w-full text-center text-indigo-600 font-semibold text-sm">Ver Detalles
                                &rarr;</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay maquinaria disponible</h3>
                        <p class="mt-1 text-sm text-gray-500">Vuelve a intentarlo más tarde.</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $machines->links() }}
            </div>
        </div>
    </div>
@endsection