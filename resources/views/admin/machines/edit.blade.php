@extends('admin.layouts.app')

@section('title', isset($machine) ? 'Editar Maquinaria' : 'Nueva Maquinaria')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                    {{ isset($machine) ? 'Editar: ' . $machine->name : 'Registrar Nueva Maquinaria' }}
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('admin.machines.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Volver
                </a>
            </div>
        </div>

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form
                    action="{{ isset($machine) ? route('admin.machines.update', $machine) : route('admin.machines.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($machine))
                        @method('PUT')
                    @endif

                    <div class="grid grid-cols-6 gap-6">
                        <!-- Basic Info -->
                        <div class="col-span-6 sm:col-span-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Equipo</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $machine->name ?? '') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                required>
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-2">
                            <label for="internal_code" class="block text-sm font-medium text-gray-700">Código
                                Interno</label>
                            <input type="text" name="internal_code" id="internal_code"
                                value="{{ old('internal_code', $machine->internal_code ?? '') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @error('internal_code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="brand" class="block text-sm font-medium text-gray-700">Marca</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand', $machine->brand ?? '') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="model" class="block text-sm font-medium text-gray-700">Modelo</label>
                            <input type="text" name="model" id="model" value="{{ old('model', $machine->model ?? '') }}"
                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>

                        <!-- Pricing -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="price_per_day" class="block text-sm font-medium text-gray-700">Precio por
                                Día</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="price_per_day" id="price_per_day"
                                    value="{{ old('price_per_day', $machine->price_per_day ?? '') }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="price_per_hour" class="block text-sm font-medium text-gray-700">Precio por Hora
                                (Opcional)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.01" name="price_per_hour" id="price_per_hour"
                                    value="{{ old('price_per_hour', $machine->price_per_hour ?? '') }}"
                                    class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="col-span-6 sm:col-span-3">
                            <label for="status" class="block text-sm font-medium text-gray-700">Estado</label>
                            <select id="status" name="status"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach(['available' => 'Disponible', 'rented' => 'Rentada', 'maintenance' => 'En Mantenimiento', 'reserved' => 'Reservada'] as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $machine->status ?? '') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción
                                detallada</label>
                            <div class="mt-1">
                                <textarea id="description" name="description" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">{{ old('description', $machine->description ?? '') }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Características técnicas importantes, requerimientos, etc.
                            </p>
                        </div>

                        <!-- Image -->
                        <div class="col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Imagen Principal</label>
                            <div class="mt-1 flex items-center">
                                @if(isset($machine) && $machine->image_url)
                                    <span class="h-12 w-12 rounded overflow-hidden bg-gray-100 mr-4">
                                        <img src="{{ $machine->image_url }}" alt="" class="h-full w-full object-cover">
                                    </span>
                                @endif
                                <input type="file" name="image"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection