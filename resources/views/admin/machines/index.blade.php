@extends('admin.layouts.app')

@section('title', 'Gestión de Maquinaria')

@section('content')
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Maquinaria</h1>
            <p class="mt-2 text-sm text-gray-700">Lista completa de equipos y maquinaria disponible para renta.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <a href="{{ route('admin.machines.create') }}"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                Añadir Maquinaria
            </a>
        </div>
    </div>

    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nombre
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Código
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                    Marca/Modelo</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Precio Día
                                </th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estado
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($machines as $machine)
                                                    <tr>
                                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                                            <div class="flex items-center">
                                                                <div class="h-10 w-10 flex-shrink-0">
                                                                    @if($machine->image_url)
                                                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $machine->image_url }}"
                                                                            alt="">
                                                                    @else
                                                                        <div
                                                                            class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500">
                                                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                            </svg>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                                <div class="ml-4">
                                                                    <div class="font-medium text-gray-900">{{ $machine->name }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                            {{ $machine->internal_code ?? '-' }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                            {{ $machine->brand }} / {{ $machine->model }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                            ${{ number_format($machine->price_per_day, 2) }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                                            <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 
                                                                                                {{ match ($machine->status) {
                                    'available' => 'bg-green-100 text-green-800',
                                    'rented' => 'bg-blue-100 text-blue-800',
                                    'maintenance' => 'bg-red-100 text-red-800',
                                    'reserved' => 'bg-yellow-100 text-yellow-800',
                                    default => 'bg-gray-100 text-gray-800'
                                } }}">
                                                                {{ ucfirst($machine->status) }}
                                                            </span>
                                                        </td>
                                                        <td
                                                            class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                            <a href="{{ route('admin.machines.edit', $machine) }}"
                                                                class="text-indigo-600 hover:text-indigo-900 mr-4">Editar</a>
                                                        </td>
                                                    </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-4 text-sm text-center text-gray-500">
                                        No hay maquinaria registrada.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        {{ $machines->links() }}
    </div>
@endsection