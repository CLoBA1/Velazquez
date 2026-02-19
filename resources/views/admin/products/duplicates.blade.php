@extends('admin.layouts.app')

@section('title', 'Detector de Duplicados')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Detector de Productos Duplicados') }}
            </h2>

            <!-- Search Criteria -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('admin.duplicates.index') }}" class="flex items-end gap-4">
                    <div class="flex-1">
                        <x-input-label for="criteria" :value="__('Buscar por:')" />
                        <select name="criteria" id="criteria" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="name" {{ $criteria == 'name' ? 'selected' : '' }}>Nombre Exacto (Repetido)</option>
                            <option value="code" {{ $criteria == 'code' ? 'selected' : '' }}>Código Interno (Repetido)</option>
                        </select>
                    </div>
                    <div>
                        <x-primary-button class="h-10">
                            {{ __('Buscar Duplicados') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Results -->
            @if(count($duplicates) > 0)
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 mb-2">Se encontraron <strong>{{ count($duplicates) }}</strong> grupos de duplicados.</p>
                    
                    @foreach($duplicates as $key => $group)
                        <div x-data="{ open: false }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                            <button @click="open = !open" class="w-full flex justify-between items-center p-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left focus:outline-none">
                                <div>
                                    <span class="font-bold text-lg text-gray-800">{{ $key }}</span>
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        {{ count($group) }} coincidencias
                                    </span>
                                </div>
                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" class="p-4 border-t border-gray-200">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($group as $product)
                                                <tr>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->id }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->internal_code ?? '-' }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 font-medium">{{ $product->name }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">${{ $product->public_price }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500">{{ $product->stock }}</td>
                                                    <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3" target="_blank">Editar</a>
                                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Seguro que quieres eliminar este duplicado?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                @if(request()->has('criteria'))
                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    ¡Excelente! No se encontraron duplicados con el criterio seleccionado.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
