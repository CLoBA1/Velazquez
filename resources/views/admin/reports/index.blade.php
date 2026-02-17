@extends('admin.layouts.app')

@section('content')
    <div class="p-6">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-slate-800">Centro de Reportes</h1>
            <p class="text-slate-500">Genera y descarga reportes detallados de tu negocio.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Inventory Report Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Inventario Global</h2>
                            <p class="text-sm text-slate-500">Lista completa de productos y stock actual.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.reports.inventory') }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Filtrar por Área</label>
                        <select name="business_line"
                            class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            <option value="">Todo el Inventario</option>
                            <option value="hardware">Ferretería</option>
                            <option value="construction">Materiales</option>
                            <option value="machinery">Maquinaria</option>
                        </select>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit" name="format" value="excel"
                            class="flex-1 flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-4 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Excel
                        </button>
                        <button type="submit" name="format" value="pdf"
                            class="flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors shadow-lg shadow-red-900/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v15a2 2 0 002 2z">
                                </path>
                            </svg>
                            PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Movements Report Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-purple-50 text-purple-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Kardex de Movimientos</h2>
                            <p class="text-sm text-slate-500">Historial de entradas, salidas y ajustes.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.reports.movements') }}" method="GET" class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Desde</label>
                            <input type="date" name="start_date"
                                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Hasta</label>
                            <input type="date" name="end_date"
                                class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Filtrar por Área</label>
                        <select name="business_line"
                            class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                            <option value="">Todos los Movimientos</option>
                            <option value="hardware">Ferretería</option>
                            <option value="construction">Materiales</option>
                            <option value="machinery">Maquinaria</option>
                        </select>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="submit" name="format" value="excel"
                            class="flex-1 flex items-center justify-center gap-2 bg-white border border-slate-200 hover:border-purple-500 hover:text-purple-600 text-slate-700 font-bold py-2.5 px-4 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Excel
                        </button>
                        <button type="submit" name="format" value="pdf"
                            class="flex-1 flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 px-4 rounded-lg transition-colors shadow-lg shadow-red-900/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 2H7a2 2 0 00-2 2v15a2 2 0 002 2z">
                                </path>
                            </svg>
                            PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Future Placeholders -->
            <div
                class="bg-gray-50 rounded-xl border border-dashed border-gray-200 p-6 flex flex-col items-center justify-center text-center opacity-60">
                <div class="p-3 bg-gray-100 text-gray-400 rounded-lg mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-500">Reporte de Ventas</h3>
                <p class="text-xs text-gray-400">Próximamente</p>
            </div>

            <div
                class="bg-gray-50 rounded-xl border border-dashed border-gray-200 p-6 flex flex-col items-center justify-center text-center opacity-60">
                <div class="p-3 bg-gray-100 text-gray-400 rounded-lg mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-500">Reporte de Clientes</h3>
                <p class="text-xs text-gray-400">Próximamente</p>
            </div>

        </div>
    </div>
@endsection