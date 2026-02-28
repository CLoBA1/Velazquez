@extends('admin.layouts.app')

@section('title', 'Nuevo producto')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Nuevo producto</h1>
            <p class="text-slate-500 mt-1">Registra un nuevo artículo en el catálogo.</p>
        </div>

        <a href="{{ session('admin_products_url', route('admin.products.index')) }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 shadow-sm">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="space-y-1">
                    <p class="font-bold text-red-900">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc leading-5 pl-5 text-sm text-red-800 space-y-1 pt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" x-data="{ 
                            business_line: '{{ old('business_line', 'hardware') }}',
                            barcode: '{{ old('barcode') }}'
                        }" @scan-completed.window="barcode = $event.detail.code">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Columna Izquierda: Detalles --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Card: Información Principal --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Información Principal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Línea de Negocio</label>
                            <select name="business_line" x-model="business_line"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="hardware">Ferretería General</option>
                                <option value="construction">Materiales de Construcción</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nombre del Producto</label>
                            <input name="name" value="{{ old('name') }}" required
                                placeholder="Ej: Taladro Percutor 1/2'' 650W"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Descripción</label>
                            <textarea name="description" rows="4" required
                                placeholder="Detalles técnicos, características y uso..."
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Card: Clasificación --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </span>
                        Clasificación y Códigos
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Familia --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Familia</label>
                            <select id="family_id"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm bg-slate-50 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="">Seleccionar...</option>
                                @foreach($families as $f)
                                    <option value="{{ $f->id }}">{{ $f->name }} ({{ $f->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Categoría</label>
                            <select id="category_id" name="category_id" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="">Seleccionar Categoría</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" data-family-id="{{ $c->family_id }}"
                                        @selected(old('category_id') == $c->id)>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Marca --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Marca <span
                                    x-show="business_line === 'hardware'" class="text-red-500">*</span></label>
                            <select name="brand_id" :required="business_line === 'hardware'"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="">Seleccionar Marca</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" @selected(old('brand_id') == $b->id)>{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Unidad --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Unidad de Medida (Base)</label>
                            <select name="unit_id" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <option value="">Seleccionar Unidad</option>
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" @selected(old('unit_id') == $u->id)>
                                        {{ $u->name }} ({{ $u->symbol }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-400 mt-1">La unidad mínima de venta (ej: Metro, Pieza).</p>
                        </div>

                        {{-- Código Interno Generator --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Código Interno</label>
                            <div class="flex gap-2">
                                <input id="internal_code" name="internal_code" value="{{ old('internal_code') }}" required
                                    class="flex-1 rounded-xl border-slate-200 py-2.5 px-3 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <button id="btnGenerate" type="button"
                                    class="shrink-0 rounded-xl bg-slate-100 border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-colors">
                                    Generar Automático
                                </button>
                            </div>
                            <p id="genMsg" class="text-xs text-slate-400 mt-2 ml-1">Selecciona una familia para generar el
                                código.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Código de Barras (Opcional)</label>
                            <div class="flex gap-2">
                                <input name="barcode" x-model="barcode"
                                    class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                <button type="button" @click="$dispatch('open-scanner')"
                                    class="shrink-0 rounded-xl bg-slate-100 border border-slate-200 px-3 text-slate-600 hover:bg-slate-200 hover:text-slate-800 transition-colors"
                                    title="Escanear código de barras">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 17h.01M9 17h.01M12 13h.01M12 21h4a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-1.923-.641a1 1 0 00-.578.024l-1.075.358a1 1 0 00-.684.948V21zM6.75 8.25A2.25 2.25 0 019 6h6a2.25 2.25 0 012.25 2.25v2.25a2.25 2.25 0 01-2.25 2.25H9a2.25 2.25 0 01-2.25-2.25V8.25z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">SKU Proveedor (Opcional)</label>
                            <input name="supplier_sku" value="{{ old('supplier_sku') }}"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                    </div>
                </div>

                {{-- Card: Gestión de Precios Unificado --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" x-data="priceControl({
                                    cost: {{ Js::from(old('cost_price', 0)) }},
                                    tax_percent: {{ Js::from(old('taxes_percent', 0)) }},
                                    base_public_price: {{ Js::from(old('public_price', '')) }},
                                    units: {{ Js::from(old('units', [])) }}
                                })">

                    {{-- Header de la Tarjeta --}}
                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span
                                class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </span>
                            Control de Precios
                        </h2>
                    </div>

                    <div class="p-8 space-y-12">

                        {{-- SECCIÓN 1: COSTO Y VENTA --}}
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">1. Configuración de
                                Precio Base (Pieza)</h3>

                            <div class="flex flex-col lg:flex-row gap-6">

                                {{-- Bloque de Costo --}}
                                <div class="flex-1 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-5">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <h4 class="text-[11px] font-black text-slate-500 uppercase tracking-wider">
                                            Estructura de Costo</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                                        {{-- Costo Compra --}}
                                        <div>
                                            <label
                                                class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Costo
                                                Compra</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-slate-400 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" name="cost_price" x-model="cost"
                                                    class="w-full pl-7 pr-3 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm transition-all"
                                                    placeholder="0.00">
                                            </div>
                                        </div>

                                        {{-- IVA --}}
                                        <div>
                                            <label
                                                class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">IVA
                                                %</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0" name="taxes_percent"
                                                    x-model="tax_percent"
                                                    class="w-full px-4 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm transition-all"
                                                    placeholder="0">
                                                <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Costo Neto (Label Only) --}}
                                        <div class="pb-1.5 pl-2">
                                            <label
                                                class="block text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Costo
                                                Neto Calc.</label>
                                            <div class="text-xl font-black text-blue-600 tracking-tight">
                                                <span x-text="'$' + net_cost.toFixed(2)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Separator Arrow --}}
                                <div class="hidden lg:flex items-center justify-center text-slate-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                                    </svg>
                                </div>

                                {{-- Bloque de Venta --}}
                                <div
                                    class="flex-[0.8] bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <h4 class="text-[11px] font-black text-emerald-700 uppercase tracking-wider">Precio
                                            al Público</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                                        {{-- Margen Base --}}
                                        <div>
                                            <label
                                                class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Margen</label>
                                            <div class="relative">
                                                <input type="number" step="any" x-model="base_margin"
                                                    @input="updateBasePrice()" placeholder="Ganancia"
                                                    class="w-full pl-4 pr-8 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm transition-all">
                                                <span class="absolute right-4 top-2.5 text-emerald-400 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Precio Público --}}
                                        <div>
                                            <label
                                                class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Precio
                                                Venta</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-emerald-500 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" name="public_price"
                                                    x-model="base_public_price" @input="updateBaseMargin()" required
                                                    class="w-full pl-8 pr-3 py-2.5 rounded-xl border-emerald-400 text-sm font-black text-emerald-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-md transition-all">

                                                {{-- Hidden Fields --}}
                                                <input type="hidden" name="sale_price" :value="base_public_price">
                                                <input type="hidden" name="mid_wholesale_price" :value="base_public_price">
                                                <input type="hidden" name="wholesale_price" :value="base_public_price">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- SECCIÓN 2: PRESENTACIONES ADICIONALES --}}
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-4">
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">2. Precios por
                                    Presentación</h3>
                                <button type="button" @click="addUnit()"
                                    class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar Variante
                                </button>
                            </div>

                            <p class="text-sm text-slate-500 mb-6" x-show="units.length === 0">
                                No se han agregado presentaciones adicionales (ej: Caja, Charola, Rollo).
                            </p>

                            {{-- Encabezados de Tabla (Solo visibles en Desktop) --}}
                            <div class="hidden lg:flex items-center gap-4 px-2 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 mb-2 pr-14"
                                x-show="units.length > 0">
                                <div class="w-48">Presentación</div>
                                <div class="w-32">Costo Base</div>
                                <div class="w-24">IVA %</div>
                                <div class="w-32 text-blue-600">Neto Calc.</div>
                                <div class="w-28 text-emerald-600">Margen %</div>
                                <div class="w-40 text-emerald-600">Precio Público</div>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(unit, index) in units" :key="index">
                                    <div
                                        class="flex flex-col lg:flex-row gap-4 lg:items-center px-4 py-3 lg:px-2 rounded-xl border border-slate-200 lg:border-transparent lg:border-b hover:bg-slate-50 transition-colors relative pr-14 shadow-sm lg:shadow-none">

                                        <button type="button" @click="removeUnit(index)"
                                            class="absolute top-3 right-3 lg:top-1/2 lg:-translate-y-1/2 text-slate-300 hover:text-red-500 transition-colors bg-white hover:bg-red-50 rounded-lg p-1.5 border border-slate-100"
                                            title="Eliminar presentación">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>

                                        {{-- Unidad --}}
                                        <div class="w-full lg:w-48">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Unidad</label>
                                            <select :name="'units['+index+'][unit_id]'" x-model="unit.unit_id" required
                                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-2 py-2">
                                                <option value="">Selección...</option>
                                                @foreach($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Costo Compra --}}
                                        <div class="w-full lg:w-32">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Costo
                                                Base</label>
                                            <div class="relative">
                                                <span class="absolute left-2.5 top-2 text-slate-400 font-bold">$</span>
                                                <input type="number" step="0.01" min="0"
                                                    :name="'units['+index+'][cost_price]'" x-model="unit.cost_price"
                                                    @input="updateUnitCost(unit)" required
                                                    class="w-full pl-6 pr-2 py-2 rounded-lg border-slate-300 text-sm focus:border-blue-500">
                                            </div>
                                        </div>

                                        {{-- IVA --}}
                                        <div class="w-full lg:w-24">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">IVA
                                                %</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0"
                                                    :name="'units['+index+'][taxes_percent]'" x-model="unit.taxes_percent"
                                                    @input="updateUnitCost(unit)"
                                                    class="w-full px-3 py-2 rounded-lg border-slate-300 text-sm focus:border-blue-500">
                                            </div>
                                        </div>

                                        {{-- Costo Neto Calc (Text Only) --}}
                                        <div class="w-full lg:w-32 flex flex-col justify-center">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-blue-500 uppercase mb-1">Neto
                                                Calc.</label>
                                            <span class="text-base font-black text-blue-600"
                                                x-text="'$' + (unit.calculated_cost || '0.00')"></span>
                                        </div>

                                        {{-- Margen Unit --}}
                                        <div class="w-full lg:w-28">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-emerald-600 uppercase mb-1">Margen
                                                %</label>
                                            <div class="relative">
                                                <input type="number" step="any" x-model="unit.margin"
                                                    @input="updateUnitPrice(unit)"
                                                    class="w-full pl-3 pr-7 py-2 rounded-lg border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500">
                                                <span class="absolute right-3 top-2 text-emerald-500 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Precio Presentación --}}
                                        <div class="w-full lg:w-40">
                                            <label
                                                class="block lg:hidden text-[10px] font-bold text-emerald-600 uppercase mb-1">Precio
                                                Público</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-emerald-500 font-bold">$</span>
                                                <input type="number" step="0.01" min="0"
                                                    :name="'units['+index+'][public_price]'" x-model="unit.public_price"
                                                    @input="updateUnitMargin(unit)" required
                                                    class="w-full pl-7 pr-2 py-2 rounded-lg border-emerald-400 text-sm font-black text-emerald-800 bg-emerald-50 focus:border-emerald-500 shadow-sm">
                                            </div>

                                            {{-- Hidden fields --}}
                                            <input type="hidden" :name="'units['+index+'][sale_price]'"
                                                :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][mid_wholesale_price]'"
                                                :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][wholesale_price]'"
                                                :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][conversion_factor]'" value="1">
                                            <input type="hidden" :name="'units['+index+'][barcode]'" value="">
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Card: Imagen --}}
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </span>
                        Fotografía
                    </h2>

                    <div class="flex items-center justify-center w-full">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-slate-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="text-xs text-slate-500 font-semibold">Clic para subir imagen</p>
                                <p class="text-[10px] text-slate-400">SVG, PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" name="main_image" accept="image/*" class="hidden" />
                        </label>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit"
                        class="w-full rounded-xl bg-slate-900 py-3.5 text-sm font-bold text-white shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all hover:scale-[1.02]">
                        Guardar Producto
                    </button>
                    <a href="{{ route('admin.products.index') }}"
                        class="w-full rounded-xl border border-slate-200 bg-white py-3.5 text-center text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancelar
                    </a>
                </div>
            </div>
        </div>
    </form>

    <x-scanner-modal />

    <script>
        (function () {
            const familySelect = document.getElementById('family_id');
            const categorySelect = document.getElementById('category_id');
            const btn = document.getElementById('btnGenerate');
            const internalCodeInput = document.getElementById('internal_code');
            const msg = document.getElementById('genMsg');

            function filterCategories() {
                const fam = familySelect.value;
                const options = Array.from(categorySelect.options);

                options.forEach((opt, idx) => {
                    if (idx === 0) return; // placeholder
                    const familyId = opt.getAttribute('data-family-id');
                    opt.hidden = fam ? (familyId !== fam) : false;
                });

                // si la categoría seleccionada ya no coincide, reset
                const selectedOpt = categorySelect.options[categorySelect.selectedIndex];
                if (selectedOpt && selectedOpt.hidden) {
                    categorySelect.value = '';
                }
            }

            familySelect.addEventListener('change', filterCategories);

            btn.addEventListener('click', async () => {
                const fam = familySelect.value;
                if (!fam) {
                    msg.textContent = 'Selecciona una familia primero.';
                    msg.className = 'text-xs mt-2 ml-1 text-red-600 font-medium';
                    return;
                }

                btn.disabled = true;
                msg.textContent = 'Generando...';
                msg.className = 'text-xs mt-2 ml-1 text-slate-500';

                try {
                    const res = await fetch("{{ route('admin.products.generate_code') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ family_id: fam })
                    });

                    if (!res.ok) {
                        const txt = await res.text();
                        throw new Error(txt || 'Error al generar');
                    }

                    const data = await res.json();
                    internalCodeInput.value = data.internal_code;
                    msg.textContent = 'Código generado correctamente.';
                    msg.className = 'text-xs mt-2 ml-1 text-emerald-600 font-medium';
                } catch (e) {
                    msg.textContent = 'No se pudo generar. Revisa conexión.';
                    msg.className = 'text-xs mt-2 ml-1 text-red-600 font-medium';
                    console.error(e);
                } finally {
                    btn.disabled = false;
                }
            });

            // inicial
            filterCategories();
        })();
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('priceControl', (initialData) => ({
                cost: initialData.cost,
                tax_percent: initialData.tax_percent,
                net_cost: 0,

                // Base Unit Prices
                base_public_price: initialData.base_public_price,
                base_margin: '',

                // Additional Units
                units: initialData.units || [],

                addUnit() {
                    this.units.push({
                        unit_id: '',
                        cost_price: 0,
                        taxes_percent: 0,
                        calculated_cost: 0,
                        public_price: '',
                        margin: ''
                    });
                },

                removeUnit(index) {
                    this.units.splice(index, 1);
                },

                // Base Unit Calculation Logic
                updateGrossCost() {
                    let c = parseFloat(this.cost) || 0;
                    let t = parseFloat(this.tax_percent) || 0;
                    this.net_cost = c * (1 + (t / 100));
                    this.updateBaseMargin();
                },

                updateBaseMargin() {
                    let p = parseFloat(this.base_public_price);
                    if (this.net_cost > 0 && !isNaN(p)) {
                        this.base_margin = (((p / this.net_cost) - 1) * 100).toFixed(2);
                    } else {
                        this.base_margin = '';
                    }
                },

                updateBasePrice() {
                    let m = parseFloat(this.base_margin);
                    if (this.net_cost > 0 && !isNaN(m)) {
                        this.base_public_price = (this.net_cost * (1 + (m / 100))).toFixed(2);
                    }
                },

                // Extra Units Calculation Logic
                updateUnitCost(unit) {
                    let c = parseFloat(unit.cost_price) || 0;
                    let t = parseFloat(unit.taxes_percent) || 0;
                    let n = c * (1 + (t / 100));
                    unit.calculated_cost = n.toFixed(2);
                    this.updateUnitMargin(unit);
                },

                updateUnitMargin(unit) {
                    let cost = parseFloat(unit.calculated_cost) || 0;
                    let price = parseFloat(unit.public_price) || 0;
                    if (cost > 0 && price > 0) {
                        unit.margin = (((price / cost) - 1) * 100).toFixed(2);
                    } else {
                        unit.margin = '';
                    }
                },

                updateUnitPrice(unit) {
                    let cost = parseFloat(unit.calculated_cost) || 0;
                    let margin = parseFloat(unit.margin) || 0;
                    if (cost > 0 && !isNaN(margin)) {
                        unit.public_price = (cost * (1 + (margin / 100))).toFixed(2);
                    }
                },

                init() {
                    this.updateGrossCost();
                    this.$watch('cost', () => this.updateGrossCost());
                    this.$watch('tax_percent', () => this.updateGrossCost());
                    this.$watch('base_public_price', () => this.updateBaseMargin());

                    // Make sure DB-loaded units have calculated nets
                    this.units.forEach(u => this.updateUnitCost(u));
                }
            }));
        });
    </script>

@endsection