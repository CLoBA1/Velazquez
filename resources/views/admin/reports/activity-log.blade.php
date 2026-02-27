@extends('admin.layouts.app')

@section('title', 'Historial de Modificaciones')

@section('content')
    <div class="py-12">
        <div class="max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">
                {{ __('Historial de Modificaciones (Auditoría)') }}
            </h2>
            <p class="text-sm text-gray-600 mb-6">Revisa qué cambios se han hecho a los productos, quién los hizo y a qué
                hora exacta.</p>

            <!-- Filtros Avanzados -->
            <div class="bg-white p-4 shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <form method="GET" action="{{ route('admin.reports.activity-log') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                    
                    <!-- Búsqueda por Producto -->
                    <div class="md:col-span-2">
                        <label for="search" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Buscar Producto</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nombre o Código Interno..." 
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>

                    <!-- Filtro por Usuario -->
                    <div>
                        <label for="user_id" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Usuario</label>
                        <select name="user_id" id="user_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="">Todos los Usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fechas -->
                    <div class="grid grid-cols-2 gap-2 md:col-span-2">
                        <div>
                            <label for="date_start" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Desde</label>
                            <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label for="date_end" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Hasta</label>
                                <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}" 
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 rounded shadow-sm text-sm h-[38px] flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha / Hora</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario Responsable</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto (ID)</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detalles del Cambio</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($activities as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 font-medium">
                                            {{ $activity->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $activity->created_at->format('h:i:s A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold text-xs uppercase">
                                                {{ substr($activity->causer->name ?? '?', 0, 2) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ $activity->causer->name ?? 'Sistema / Externo' }}</p>
                                                <p class="text-xs text-gray-500">{{ $activity->causer->email ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            ID: {{ $activity->subject_id }}
                                            @if($activity->subject_type === 'App\Models\ProductUnit')
                                                (Presentación)
                                            @endif
                                        </span>
                                        <p class="text-sm text-gray-900 mt-1 font-semibold max-w-xs truncate">
                                            @if($activity->subject_type === 'App\Models\ProductUnit')
                                                {{ $activity->subject->product->name ?? 'Producto Eliminado' }} - <span class="text-xs text-orange-600 font-bold uppercase">{{ $activity->subject->unit->name ?? '?' }}</span>
                                            @else
                                                {{ $activity->subject->name ?? 'Producto Eliminado' }}
                                            @endif
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($activity->description === 'updated')
                                            @php
                                                $changes = $activity->changes;
                                                $old = $changes['old'] ?? [];
                                                $attributes = $changes['attributes'] ?? [];
                                                
                                                // Definimos cuáles campos queremos mostrar con su respectiva etiqueta en español
                                                $allowedFields = [
                                                    'cost_price' => 'Precio de Compra',
                                                    'public_price' => 'Precio Público'
                                                ];
                                            @endphp
                                            <ul class="space-y-2">
                                                @foreach($attributes as $key => $newValue)
                                                    @if(array_key_exists($key, $allowedFields) && array_key_exists($key, $old))
                                                        <li
                                                            class="bg-gray-50 p-2 rounded border border-gray-100 flex flex-col gap-1 text-xs">
                                                            <strong class="uppercase text-gray-700 font-bold text-orange-600">{{ $allowedFields[$key] }}</strong>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-red-600 bg-red-50 px-1 rounded line-through"
                                                                    title="Antes">${{ $old[$key] ?? '0.00' }}</span>
                                                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                                                </svg>
                                                                <span class="text-green-700 bg-green-50 px-1 rounded font-bold"
                                                                    title="Nuevo">${{ $newValue ?? '0.00' }}</span>
                                                            </div>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @elseif($activity->description === 'created')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                                Producto Creado
                                            </span>
                                        @elseif($activity->description === 'deleted')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                Producto Eliminado
                                            </span>
                                        @else
                                            {{ $activity->description }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 whitespace-nowrap text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="mt-4 text-sm text-gray-500">Aún no hay modificaciones registradas en el
                                            catálogo.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection