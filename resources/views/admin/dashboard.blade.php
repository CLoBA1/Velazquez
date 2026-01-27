@extends('admin.layouts.app')

@section('title','Dashboard')

@section('content')
    <style>
        /* ===== Premium micro-interactions (sin JS) ===== */

        /* Quita el marker default */
        summary::-webkit-details-marker { display: none; }

        /* Animación de entrada (stagger) */
        .reveal {
            opacity: 0;
            transform: translateY(10px);
            animation: revealIn 520ms cubic-bezier(.2,.9,.2,1) forwards;
            will-change: opacity, transform;
        }
        @keyframes revealIn {
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="space-y-8">
        {{-- HERO / HEADER (Standardized) --}}
        <div class="relative bg-dark rounded-3xl p-8 overflow-hidden shadow-2xl">
            <!-- Background Decor -->
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-slate-800 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-orange-900 rounded-full blur-3xl opacity-30"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                        <span class="bg-gradient-to-br from-secondary to-yellow-600 w-3 h-8 rounded-full shadow-lg shadow-yellow-500/50"></span>
                        Dashboard
                    </h1>
                    <p class="text-slate-400 mt-2 text-lg font-medium pl-6">Resumen general y accesos rápidos.</p>
                </div>
    
                <div class="flex items-center gap-3 pl-6 md:pl-0">
                    <a href="{{ route('admin.products.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-white/10 backdrop-blur-md px-5 py-3 text-sm font-bold text-white hover:bg-white/20 transition-all border border-white/10">
                        Ver Productos
                    </a>
                    <a href="{{ route('admin.products.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-bold text-white hover:bg-blue-700 shadow-xl shadow-blue-900/40 transition-all hover:-translate-y-1 hover:shadow-blue-900/60">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nuevo Producto
                    </a>
                </div>
            </div>
        </div>

        {{-- KPIs --}}
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Products -->
            <div class="reveal rounded-[2rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 transition hover:-translate-y-1 hover:shadow-2xl">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Productos</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">
                            {{ $kpis['total_products'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-indigo-50 p-3.5 text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v3H3V7zm0 5h20v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-5z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Inventory Value -->
            <div class="reveal rounded-[2rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 transition hover:-translate-y-1 hover:shadow-2xl" style="animation-delay: 60ms;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Valor (Costo)</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">
                            ${{ number_format($kpis['inventory_value'], 2) }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 p-3.5 text-emerald-600">
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2 1 7l11 5 9-4.09V17h2V7L12 2ZM3 10v8l9 4 9-4v-8l-9 4-9-4Z"/></svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="reveal rounded-[2rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 transition hover:-translate-y-1 hover:shadow-2xl" style="animation-delay: 120ms;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Stock Bajo</p>
                        <p class="mt-2 text-3xl font-black tracking-tight {{ $kpis['low_stock'] > 0 ? 'text-red-600' : 'text-slate-900' }}">
                            {{ $kpis['low_stock'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-yellow-50 p-3.5 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003zM12 8.25a.75.75 0 01.75.75v3.75a.75.75 0 01-1.5 0V9a.75.75 0 01.75-.75zm0 8.25a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" /></svg>
                    </div>
                </div>
                @if($kpis['low_stock'] > 0)
                <div class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-red-50 px-2 py-0.5 text-[10px] font-bold text-red-600 border border-red-100">
                    <span class="relative flex h-1.5 w-1.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                    </span>
                    Requieren atención
                </div>
                @endif
            </div>

            <!-- Today's Movements -->
            <div class="reveal rounded-[2rem] border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 transition hover:-translate-y-1 hover:shadow-2xl" style="animation-delay: 180ms;">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Movimientos Hoy</p>
                        <p class="mt-2 text-3xl font-black tracking-tight text-slate-900">
                            {{ $kpis['movements_today'] }}
                        </p>
                    </div>
                    <div class="rounded-2xl bg-blue-50 p-3.5 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor"><path d="M7 2h10a2 2 0 0 1 2 2v2H5V4a2 2 0 0 1 2-2Zm12 6H5v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8ZM8 12h8v2H8v-2Zm0 4h6v2H8v-2Z"/></svg>
                    </div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('admin.inventory.movements') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">Ver historial &rarr;</a>
                </div>
            </div>
        </div>

        {{-- CHART SECTION --}}
        <div class="reveal grid grid-cols-1 lg:grid-cols-3 gap-6" style="animation-delay: 240ms;">
            <div class="lg:col-span-3 rounded-[2rem] border border-slate-100 bg-white p-8 shadow-xl shadow-slate-200/50">
                <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <span class="w-1 h-5 bg-secondary rounded-full"></span>
                    Movimientos últimos 7 días
                </h3>
                <div id="movementsChart" class="w-full h-64"></div>
            </div>
        </div>

        {{-- Scripts for Chart --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var options = {
                    series: [{
                        name: 'Movimientos',
                        data: @json($chartValues)
                    }],
                    chart: {
                        type: 'area', // or bar
                        height: 250,
                        toolbar: { show: false },
                        zoom: { enabled: false },
                        fontFamily: 'inherit'
                    },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 3, colors: ['#FFD700'] }, // Regal Gold
                    xaxis: {
                        categories: @json($chartLabels),
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: { style: { colors: '#94a3b8', fontSize: '12px', fontWeight: 600 } }
                    },
                    yaxis: { show: false },
                    fill: {
                        type: 'gradient',
                        gradient: { shadeIntensity: 1, opacityFrom: 0.5, opacityTo: 0.1, stops: [0, 90, 100], colorStops: [
                            { offset: 0, color: '#FFD700', opacity: 0.5 },
                            { offset: 100, color: '#FFD700', opacity: 0.05 },
                        ]} // Gold gradient
                    },
                    theme: { monochrome: { enabled: false } }, // Custom colors
                    grid: { show: true, borderColor: '#f1f5f9', strokeDashArray: 4, padding: { top: 0, right: 0, bottom: 0, left: 10 } }
                };

                var chart = new ApexCharts(document.querySelector("#movementsChart"), options);
                chart.render();
            });
        </script>

        {{-- SECTION HEADER --}}
        <div class="flex items-end justify-between gap-3 pt-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Últimos productos</h2>
                <p class="mt-1 text-sm text-slate-500 font-medium">Vista rápida a tu catálogo reciente.</p>
            </div>

            <a href="{{ route('admin.products.index') }}"
               class="text-sm font-bold text-info hover:text-blue-800 hover:underline">
                Ver todos
            </a>
        </div>

        {{-- CARDS --}}
        @if(($products ?? collect())->isEmpty())
            <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white p-12 text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-slate-50 p-4 rounded-full">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                </div>
                <p class="text-base font-bold text-slate-900">No hay productos para mostrar</p>
                <div class="mt-4">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white transition hover:bg-black shadow-lg shadow-slate-900/20">
                        + Crear primer producto
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $p)
                    <div class="group relative flex flex-col overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-lg shadow-slate-200/50 transition-all duration-300 hover:shadow-xl hover:translate-y-[-4px]">
                        
                        {{-- Image Area --}}
                        <div class="aspect-[4/3] w-full overflow-hidden bg-slate-50 relative border-b border-slate-100">
                            @if($p->main_image_path)
                                <img src="{{ \Illuminate\Support\Facades\Storage::url($p->main_image_path) }}" 
                                     alt="{{ $p->name }}" 
                                     class="h-full w-full object-contain p-6 transition-transform duration-500 group-hover:scale-110 mix-blend-multiply">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-slate-200">
                                    <svg class="w-16 h-16 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            
                            {{-- Badges --}}
                            <div class="absolute top-3 left-3 flex flex-col gap-1">
                                @if($p->category)
                                    <span class="inline-flex items-center rounded-lg bg-white/90 backdrop-blur px-2.5 py-1 text-[10px] font-bold text-slate-600 shadow-sm border border-slate-200">
                                        {{ $p->category->name }}
                                    </span>
                                @endif
                            </div>

                            {{-- Stock Status --}}
                            <div class="absolute top-3 right-3">
                                @if($p->stock <= $p->min_stock)
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-[10px] font-bold text-red-700 shadow-sm border border-red-200">
                                        Bajo: {{ $p->stock }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2.5 py-1 text-[10px] font-bold text-emerald-700 shadow-sm border border-emerald-200">
                                        {{ $p->stock }} {{ $p->unit->symbol ?? ''}}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex flex-1 flex-col p-5">
                            <div class="mb-4">
                                <h3 class="text-sm font-bold text-slate-900 group-hover:text-secondary transition-colors line-clamp-2 leading-snug" title="{{ $p->name }}">
                                    {{ $p->name }}
                                </h3>
                                <div class="mt-2 flex items-center gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    <span>{{ $p->brand->name ?? 'Sin Marca' }}</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-4 border-t border-slate-50 flex items-end justify-between">
                                <div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mb-0.5">Público</p>
                                    <p class="text-lg font-black text-slate-900">
                                        ${{ number_format($p->public_price, 2) }}
                                    </p>
                                </div>
                                
                                <a href="{{ route('admin.products.edit', $p) }}" class="rounded-xl p-2 bg-slate-50 text-slate-400 hover:bg-primary hover:text-white transition-colors" title="Editar Producto">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection