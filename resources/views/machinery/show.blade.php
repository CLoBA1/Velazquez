@extends('layouts.store')

@section('content')
    <div class="bg-white">
        <div class="max-w-2xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
                <!-- Image gallery -->
                <div class="flex flex-col-reverse">
                    <div
                        class="w-full aspect-w-1 aspect-h-1 bg-gray-100 rounded-lg overflow-hidden sm:aspect-w-2 sm:aspect-h-3">
                        @if($machine->image_url)
                            <img src="{{ $machine->image_url }}" alt="{{ $machine->name }}"
                                class="w-full h-full object-center object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400">
                                <svg class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product info -->
                <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                    <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $machine->name }}</h1>

                    <div class="mt-3">
                        <h2 class="sr-only">Información del producto</h2>
                        <p class="text-3xl text-gray-900">
                            @if($machine->price_per_day)
                                ${{ number_format($machine->price_per_day, 2) }} <span
                                    class="text-base text-gray-500 font-normal">/ día</span>
                            @else
                                Consultar precio
                            @endif
                        </p>
                        @if($machine->price_per_hour)
                            <p class="text-lg text-gray-600 mt-1">Or ${{ number_format($machine->price_per_hour, 2) }} / hora
                            </p>
                        @endif
                    </div>

                    <div class="mt-6">
                        <h3 class="sr-only">Descripción</h3>
                        <div class="text-base text-gray-700 space-y-6">
                            <p>{{ $machine->description }}</p>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-gray-200 pt-8">
                        <h3 class="text-sm font-medium text-gray-900">Especificaciones</h3>
                        <div class="mt-4 prose prose-sm text-gray-500">
                            <ul role="list">
                                <li><strong>Marca:</strong> {{ $machine->brand ?? 'N/A' }}</li>
                                <li><strong>Modelo:</strong> {{ $machine->model ?? 'N/A' }}</li>
                                <li><strong>Código Interno:</strong> {{ $machine->internal_code ?? 'N/A' }}</li>
                                <li><strong>Estado Actual:</strong>
                                    <span
                                        class="@if($machine->status == 'available') text-green-600 @else text-red-600 @endif font-bold">
                                        {{ match ($machine->status) {
        'available' => 'Disponible',
        'rented' => 'Rentada',
        'maintenance' => 'En Mantenimiento',
        'reserved' => 'Reservada',
        default => $machine->status
    } }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8">
                        @if($machine->status === 'available')
                            <!-- Rental Calculator -->
                            <div class="mb-8">
                                <livewire:machinery.rental-calculator :machine="$machine" />
                            </div>

                            <div class="relative flex py-5 items-center">
                                <div class="flex-grow border-t border-gray-300"></div>
                                <span class="flex-shrink-0 mx-4 text-gray-400 text-sm">O contactar directamente</span>
                                <div class="flex-grow border-t border-gray-300"></div>
                            </div>

                            <a href="https://wa.me/527447491902?text={{ urlencode('Hola, me interesa rentar la maquinaria: ' . $machine->name . '. ¿Podrían darme información sobre disponibilidad?') }}"
                                target="_blank"
                                class="w-full bg-green-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors gap-2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.355-5.298c0-5.457 4.432-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                Solicitar por WhatsApp
                            </a>
                            <p class="mt-4 text-sm text-gray-500 text-center">
                                Te contactaremos al instante para coordinar la entrega.
                            </p>
                        @else
                            <button type="button" disabled
                                class="w-full bg-gray-300 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white cursor-not-allowed">
                                No Disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection