<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Comprimir Taxonomía (Fusiones)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Alerts --}}
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif
            @if (session()->has('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            {{-- Type selector (shared between both panels) --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">¿Qué deseas trabajar?</label>
                <select wire:model.live="type"
                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="category">Categorías (Reasigna productos)</option>
                    <option value="family">Familias/Departamentos (Reasigna categorías enteras)</option>
                </select>
            </div>

            {{-- ─── AUTO-COMPRESS PANEL ─── --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-1">⚡ Auto-Comprimir Duplicados</h3>
                <p class="text-gray-500 text-sm mb-4">
                    Detecta automáticamente las categorías con el <strong>mismo nombre exacto</strong> y las fusiona en una sola (conservando la que tenga más productos). Ideal para limpiar rápido.
                </p>

                @if(!$showingAutoCompress && empty($autoCompressResults))
                    <button wire:click="previewAutoCompress" wire:loading.attr="disabled"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-5 rounded">
                        <span wire:loading wire:target="previewAutoCompress">Analizando...</span>
                        <span wire:loading.remove wire:target="previewAutoCompress">🔍 Analizar y Ver Duplicados</span>
                    </button>
                @endif

                @if($showingAutoCompress)
                    @if(empty($duplicateGroups))
                        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded mt-2">
                            ✅ ¡No se encontraron categorías con nombre duplicado! Tu catálogo está limpio.
                        </div>
                        <button wire:click="$set('showingAutoCompress', false)" class="mt-3 text-sm text-gray-500 underline">
                            Cerrar
                        </button>
                    @else
                        <div class="mt-2 bg-yellow-50 border border-yellow-300 rounded p-4">
                            <p class="font-semibold text-yellow-800 mb-3">
                                Se encontraron <strong>{{ count($duplicateGroups) }}</strong> grupos de categorías duplicadas. Se conservará la que tenga más productos y el resto serán eliminadas.
                            </p>
                            <div class="max-h-72 overflow-y-auto space-y-3">
                                @foreach($duplicateGroups as $group)
                                    <div class="border border-yellow-200 rounded p-3 bg-white">
                                        <p class="font-bold text-gray-800">📂 "{{ $group['name'] }}" ({{ $group['count'] }} copias)</p>
                                        <ul class="mt-1 pl-4 text-sm text-gray-600 space-y-1">
                                            @foreach($group['items'] as $item)
                                                <li class="{{ $item['id'] == $group['keep_id'] ? 'text-green-700 font-semibold' : 'text-red-600 line-through' }}">
                                                    {{ $item['id'] == $group['keep_id'] ? '✅ CONSERVAR' : '❌ ELIMINAR' }}:
                                                    {{ $item['family'] }} (ID {{ $item['id'] }}, {{ $item['products_count'] }} prods.)
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4 flex items-center gap-4">
                                @if(!$confirmingAutoCompress)
                                    <button wire:click="$set('confirmingAutoCompress', true)"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-5 rounded">
                                        🚀 Ejecutar Auto-Comprimir
                                    </button>
                                    <button wire:click="$set('showingAutoCompress', false)" class="text-sm text-gray-500 underline">
                                        Cancelar
                                    </button>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="text-red-700 font-bold text-sm">⚠️ ¿Confirmas? Esta acción no puede deshacerse.</span>
                                        <button wire:click="executeAutoCompress" wire:loading.attr="disabled"
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-5 rounded">
                                            <span wire:loading wire:target="executeAutoCompress">Procesando...</span>
                                            <span wire:loading.remove wire:target="executeAutoCompress">✅ Sí, ejecutar</span>
                                        </button>
                                        <button wire:click="$set('confirmingAutoCompress', false)" class="text-sm text-gray-500 underline">
                                            Cancelar
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endif

                @if(!empty($autoCompressResults))
                    <div class="mt-4 bg-gray-50 border border-gray-200 rounded p-4 max-h-72 overflow-y-auto">
                        <p class="font-bold text-gray-700 mb-2">Resultados:</p>
                        @foreach($autoCompressResults as $line)
                            <p class="text-sm font-mono text-gray-700">{{ $line }}</p>
                        @endforeach
                    </div>
                    <button wire:click="$set('autoCompressResults', [])" class="mt-3 text-sm text-gray-500 underline">
                        Cerrar resultados
                    </button>
                @endif
            </div>

            {{-- ─── MANUAL MERGE PANEL ─── --}}
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-1">🔧 Fusión Manual</h3>
                <p class="text-gray-600 text-sm mb-4">
                    Elige tú mismo cuál {{ $type === 'category' ? 'categoría' : 'familia' }} quieres fusionar con cuál otra.
                    <strong>Todos los productos del "Origen" se pasarán al "Destino" y el "Origen" será eliminado permanentemente.</strong>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="border p-4 rounded bg-red-50">
                        <h4 class="font-bold text-red-800 mb-2">1. Origen (Será Eliminado)</h4>
                        <select wire:model="sourceId"
                            class="w-full rounded border-gray-300 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                            <option value="">Selecciona el origen...</option>
                            @if($type === 'category')
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->family->name }} » {{ $cat->name }}
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

                    <div class="border p-4 rounded bg-green-50">
                        <h4 class="font-bold text-green-800 mb-2">2. Destino (El que se queda)</h4>
                        <select wire:model="targetId"
                            class="w-full rounded border-gray-300 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <option value="">Selecciona el destino...</option>
                            @if($type === 'category')
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->family->name }} » {{ $cat->name }}
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

                <div class="mt-6 flex justify-end items-center">
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