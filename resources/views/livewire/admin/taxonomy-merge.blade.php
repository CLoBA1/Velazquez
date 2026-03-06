<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comprimir Taxonomía (Fusiones)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <h3 class="text-lg font-bold mb-2">Herramienta de Fusión</h3>
                    <p class="text-gray-600 mb-6">
                        Utiliza esta herramienta para limpiar tu catálogo. Puedes fusionar dos Categorías (o
                        Departamentos/Familias).
                        <strong>Todos los productos del "Origen" se pasarán al "Destino" y el "Origen" será eliminado
                            permanentemente.</strong>
                    </p>

                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Selector de Tipo -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">¿Qué deseas depurar?</label>
                        <select wire:model.live="type"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                            <option value="category">Categorías (Reasigna productos)</option>
                            <option value="family">Familias/Departamentos (Reasigna categorías enteras)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Origen -->
                        <div class="border p-4 rounded bg-red-50">
                            <h4 class="font-bold text-red-800 mb-2">1. Origen (Será Eliminado)</h4>
                            <select wire:model="sourceId"
                                class="w-full rounded border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <option value="">Selecciona el origen...</option>
                                @if($type === 'category')
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->family->name }} &raquo; {{ $cat->name }}
                                            ({{ $cat->products_count }} prod.)</option>
                                    @endforeach
                                @else
                                    @foreach($families as $fam)
                                        <option value="{{ $fam->id }}">{{ $fam->name }} ({{ $fam->products_count }} prod.)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('sourceId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Destino -->
                        <div class="border p-4 rounded bg-green-50">
                            <h4 class="font-bold text-green-800 mb-2">2. Destino (El que se queda)</h4>
                            <select wire:model="targetId"
                                class="w-full rounded border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                                <option value="">Selecciona el destino...</option>
                                @if($type === 'category')
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->family->name }} &raquo; {{ $cat->name }}
                                            ({{ $cat->products_count }} prod.)</option>
                                    @endforeach
                                @else
                                    @foreach($families as $fam)
                                        <option value="{{ $fam->id }}">{{ $fam->name }} ({{ $fam->products_count }} prod.)
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('targetId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end items-center">
                        @if(!$confirmingMerge)
                            <button wire:click="confirmMerge"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Preparar Fusión
                            </button>
                        @else
                            <div class="flex items-center space-x-4">
                                <span class="text-red-600 font-bold">¿Estás seguro? Esta acción no se puede deshacer.</span>
                                <button wire:click="$set('confirmingMerge', false)"
                                    class="text-gray-600 hover:text-gray-900 font-semibold px-4">
                                    Cancelar
                                </button>
                                <button wire:click="executeMerge"
                                    class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
                                    <svg wire:loading wire:target="executeMerge"
                                        class="animate-spin -ml-1 mr-3 h-5 w-5 text-white"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    ¡Sí, Ejecutar Fusión Ya!
                                </button>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>