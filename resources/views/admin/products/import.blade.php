@extends('admin.layouts.app')

@section('title', 'Importar productos')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Importar Productos</h1>
        <p class="text-slate-500 mt-1">Sube tu catálogo masivamente usando un archivo Excel o CSV.</p>
    </div>

    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Volver
        </a>
        <a href="{{ route('admin.products.import.template') }}" target="_blank"
            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Descargar Plantilla
        </a>
    </div>
</div>

@if(session('ok'))
    <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-emerald-800 flex items-center gap-3 shadow-sm"
        role="alert">
        <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <span class="font-bold block">¡Éxito!</span>
            <span class="text-sm opacity-90">{{ session('ok') }}</span>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-100 bg-red-50 px-5 py-4 text-red-800 shadow-sm" role="alert">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="font-bold">No pudimos procesar tu archivo</h3>
        </div>
        <ul class="list-disc pl-11 space-y-1 text-sm opacity-90">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Form Card --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <form method="POST" action="{{ route('admin.products.import.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Seleccionar Archivo</label>
                    <div class="relative">
                        <input type="file" name="file" required accept=".csv,.xlsx,.xls" class="block w-full text-sm text-slate-500
                                          file:mr-4 file:py-2.5 file:px-4
                                          file:rounded-xl file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-slate-50 file:text-slate-700
                                          hover:file:bg-slate-100
                                          border border-slate-200 rounded-xl cursor-pointer bg-white file:cursor-pointer
                                          focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                    </div>
                    <p class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Soporta archivos .CSV, .XLSX y .XLS hasta 50MB.
                    </p>
                </div>

                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100">
                    <h4 class="text-sm font-bold text-blue-800 mb-2">Consideraciones Importantes</h4>
                    <ul class="text-xs text-blue-700/80 space-y-1.5 list-disc pl-4">
                        <li>El <b>Nombre</b>, <b>Familia</b>, <b>Categoría</b> y <b>Unidad</b> son obligatorios.</li>
                        <li>Si la Familia, Categoría, Marca o Unidad no existen, <b>se crearán automáticamente</b>.</li>
                        <li>Los precios (Costo, Venta, Público) deben ser numéricos.</li>
                        <li>Si el <b>Código Interno</b> ya existe en el sistema, esa fila será <b>omitida</b> para
                            evitar duplicados.</li>
                    </ul>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center w-full sm:w-auto gap-2 rounded-xl bg-slate-900 px-8 py-3 text-sm font-semibold text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Comenzar Importación
                    </button>
                </div>
            </form>
        </div>

        @if(session('import_errors'))
        @php($errs = session('import_errors'))
        <div class="rounded-2xl border border-yellow-200 bg-white shadow-sm overflow-hidden">
            <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-yellow-800">Reporte de Detalles ({{ count($errs) }})</h2>
            </div>
            <div class="max-h-96 overflow-y-auto p-0">
                <table class="min-w-full divide-y divide-yellow-100">
                    <tbody class="divide-y divide-yellow-100 bg-white">
                        @foreach($errs as $e)
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap text-xs font-bold text-yellow-800 w-20">Fila
                                    {{ $e['row'] }}
                                </td>
                                <td class="px-6 py-3 text-sm text-yellow-700">{{ $e['message'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Import History --}}
        @php
            $history = \App\Models\ImportHistory::where('user_id', auth()->id())->latest()->take(10)->get();
        @endphp
        @if($history->isNotEmpty())
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800">Historial Reciente</h3>
                </div>
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Archivo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Resumen</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($history as $h)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    {{ $h->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                    {{ $h->file_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                    <span class="text-emerald-600 font-bold">{{ $h->created_count }}</span> Nuevos |
                                    <span class="text-amber-500">{{ $h->skipped_count }}</span> Omitidos |
                                    <span class="text-red-600">{{ $h->error_count }}</span> Errores
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('admin.products.import.report', $h->id) }}"
                                        class="text-blue-600 hover:text-blue-900 font-bold">Descargar PDF</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Guide Card --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                Columnas Requeridas
            </h3>
            <div class="space-y-4">
                <div class="text-sm">
                    <p class="font-semibold text-slate-700 mb-1">Productos</p>
                    <p class="text-slate-500 text-xs">name, description (opcional)</p>
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-slate-700 mb-1">Clasificación</p>
                    <p class="text-slate-500 text-xs">familia, categoria, marca</p>
                    <p class="text-slate-400 text-[10px] mt-0.5">* Los códigos se generan auto si no se proveen.</p>
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-slate-700 mb-1">Unidades</p>
                    <p class="text-slate-500 text-xs">unidad (ej: Pieza, Litro)</p>
                </div>
                <div class="text-sm">
                    <p class="font-semibold text-slate-700 mb-1">Precios</p>
                    <p class="text-slate-500 text-xs">costo, precio_publico, precio_venta</p>
                </div>
                <div class="text-sm pt-2 border-t border-slate-200">
                    <p class="font-semibold text-slate-700 mb-1">Identificadores (Opcional)</p>
                    <p class="text-slate-500 text-xs">codigo_interno, codigo_barras, sku</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection