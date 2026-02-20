@extends('admin.layouts.app')

@section('title', 'Editar producto')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Editar producto</h1>
            <p class="text-slate-500 mt-1">Actualiza la información del producto <span
                    class="font-bold text-slate-700">{{ $product->name }}</span>.</p>
        </div>

        <a href="{{ route('admin.products.index') }}"
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

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" x-data="{ 
                    business_line: '{{ old('business_line', $product->business_line) }}',
                    barcode: '{{ old('barcode', $product->barcode) }}'
                }" @scan-completed.window="barcode = $event.detail.code">
        @csrf
        @method('PUT')

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
                            <input name="name" value="{{ old('name', $product->name) }}" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Descripción</label>
                            <textarea name="description" rows="4" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">{{ old('description', $product->description) }}</textarea>
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
                        {{-- Categoría --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Categoría</label>
                            <select name="category_id" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>
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
                                <option value="">Seleccionar...</option>
                                @foreach($brands as $b)
                                    <option value="{{ $b->id }}" @selected(old('brand_id', $product->brand_id) == $b->id)>
                                        {{ $b->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Unidad --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Unidad de Medida</label>
                            <select name="unit_id" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                @foreach($units as $u)
                                    <option value="{{ $u->id }}" @selected(old('unit_id', $product->unit_id) == $u->id)>
                                        {{ $u->name }} ({{ $u->symbol }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Código Interno --}}
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Código Interno</label>
                            <input name="internal_code" value="{{ old('internal_code', $product->internal_code) }}" required
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
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
                            <input name="supplier_sku" value="{{ old('supplier_sku', $product->supplier_sku) }}"
                                class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                        </div>

                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Precios y Foto --}}
            <div class="space-y-6">

                {{-- Card: Precios --}}
                @if(auth()->user()->isAdmin())
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" x-data="{
                                                                                            cost: '{{ old('cost_price', $product->cost_price) }}',
                                                                                            tax_percent: '{{ old('taxes_percent', $product->taxes_percent) }}',
                                                                                            net_cost: '', 
                                                                                            init() {
                                                                                                let c = parseFloat(this.cost);
                                                                                                let t = parseFloat(this.tax_percent);
                                                                                                if(!isNaN(c) && !isNaN(t)) {
                                                                                                    if (t === 0) {
                                                                                                        this.net_cost = c;
                                                                                                    } else {
                                                                                                        this.net_cost = (c / (1 + (t / 100))).toFixed(2);
                                                                                                    }
                                                                                                }
                                                                                            },
                                                                                            updateGrossCost() {
                                                                                                let n = parseFloat(this.net_cost);
                                                                                                let t = parseFloat(this.tax_percent);
                                                                                                if(isNaN(n)) n = 0;
                                                                                                if(isNaN(t)) t = 0;

                                                                                                this.cost = (n * (1 + (t / 100))).toFixed(2);
                                                                                                // update prices logic if needed
                                                                                            },
                                                                                            calculatePrice(percent) {
                                                                                                let c = parseFloat(this.cost);
                                                                                                let p = parseFloat(percent);
                                                                                                if(isNaN(c) || isNaN(p)) return '';
                                                                                                return (c * (1 + (p / 100))).toFixed(2);
                                                                                            },
                                                                                            updatePrice(e, targetId) {
                                                                                                let val = this.calculatePrice(e.target.value);
                                                                                                if(val) document.getElementById(targetId).value = val;
                                                                                            },
                                                                                            updateMargin(e, marginId) {
                                                                                                let price = parseFloat(e.target.value);
                                                                                                let cost = parseFloat(this.cost);
                                                                                                if (isNaN(price) || isNaN(cost) || cost === 0) return;
                                                                                                let margin = ((price / cost - 1) * 100).toFixed(2);
                                                                                                document.getElementById(marginId).value = margin;
                                                                                            }
                                                                                         }">
                        <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </span>
                            Precios
                        </h2>

                        <div class="space-y-6">
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Costo
                                        de Compra</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                        <input type="number" step="0.01" min="0" x-model="net_cost" @input="updateGrossCost()"
                                            class="w-full rounded-xl border-slate-200 pl-8 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">IVA
                                        %</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="taxes_percent" x-model="tax_percent"
                                            @input="updateGrossCost()"
                                            class="w-full rounded-xl border-slate-200 pl-3 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Costo
                                    Neto</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                    <input type="number" step="0.01" min="0" name="cost_price" x-model="cost" readonly
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 pl-8 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Calculado: Costo de Compra + IVA.</p>
                            </div>

                            <hr class="border-slate-100">

                            {{-- Precio Venta --}}
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-8">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Precio
                                        de Venta (Base)</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                        <input id="sale_price" type="number" step="0.01" min="0" name="sale_price"
                                            value="{{ old('sale_price', $product->sale_price) }}" required
                                            @input="updateMargin($event, 'sale_margin')"
                                            class="w-full rounded-xl border-slate-200 pl-8 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Margen
                                        %</label>
                                    <input id="sale_margin" type="number" step="any" placeholder="%"
                                        @input="updatePrice($event, 'sale_price')"
                                        class="w-full rounded-xl border-slate-200 py-2.5 px-2 text-center text-sm font-bold text-blue-600 bg-blue-50/50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            {{-- Precio Público --}}
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-8">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Precio
                                        Público</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                        <input id="public_price" type="number" step="0.01" min="0" name="public_price"
                                            value="{{ old('public_price', $product->public_price) }}" required
                                            @input="updateMargin($event, 'public_margin')"
                                            class="w-full rounded-xl border-emerald-200 pl-8 pr-3 py-2.5 text-sm font-bold text-emerald-700 bg-emerald-50/30 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all">
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Margen
                                        %</label>
                                    <input id="public_margin" type="number" step="any" placeholder="%"
                                        @input="updatePrice($event, 'public_price')"
                                        class="w-full rounded-xl border-slate-200 py-2.5 px-2 text-center text-sm font-bold text-emerald-600 bg-blue-50/50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            {{-- Medio Mayoreo --}}
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-8">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Medio
                                        Mayoreo</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                        <input id="mid_wholesale_price" type="number" step="0.01" min="0"
                                            name="mid_wholesale_price"
                                            value="{{ old('mid_wholesale_price', $product->mid_wholesale_price) }}" required
                                            @input="updateMargin($event, 'mid_wholesale_margin')"
                                            class="w-full rounded-xl border-slate-200 pl-8 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Margen
                                        %</label>
                                    <input id="mid_wholesale_margin" type="number" step="any" placeholder="%"
                                        @input="updatePrice($event, 'mid_wholesale_price')"
                                        class="w-full rounded-xl border-slate-200 py-2.5 px-2 text-center text-sm font-bold text-blue-600 bg-blue-50/50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>
                            </div>

                            {{-- Mayoreo --}}
                            <div class="grid grid-cols-12 gap-4 items-end">
                                <div class="col-span-8">
                                    <label
                                        class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Mayoreo</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                                        <input id="wholesale_price" type="number" step="0.01" min="0" name="wholesale_price"
                                            value="{{ old('wholesale_price', $product->wholesale_price) }}" required
                                            @input="updateMargin($event, 'wholesale_margin')"
                                            class="w-full rounded-xl border-slate-200 pl-8 pr-3 py-2.5 text-sm font-semibold text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                                    </div>
                                </div>
                                <div class="col-span-4">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 text-center">Margen
                                        %</label>
                                    <input id="wholesale_margin" type="number" step="any" placeholder="%"
                                        @input="updatePrice($event, 'wholesale_price')"
                                        class="w-full rounded-xl border-slate-200 py-2.5 px-2 text-center text-sm font-bold text-blue-600 bg-blue-50/50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all placeholder:text-slate-300">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif {{-- Card: Imagen --}}
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

                    <div class="flex items-center justify-center w-full mb-4">
                        <label for="dropzone-file"
                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-xl cursor-pointer bg-slate-50 hover:bg-slate-100 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-slate-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                </svg>
                                <p class="text-xs text-slate-500 font-semibold">Clic para cambiar imagen</p>
                                <p class="text-[10px] text-slate-400">SVG, PNG, JPG (MAX. 2MB)</p>
                            </div>
                            <input id="dropzone-file" type="file" name="main_image" accept="image/*" class="hidden" />
                        </label>
                    </div>

                    @if($product->main_image_path)
                        <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-xl">
                            <img src="{{ asset('storage/' . $product->main_image_path) }}"
                                class="h-16 w-16 rounded-lg object-cover" alt="">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1"
                                    class="w-4 h-4 rounded border-slate-300 text-red-600 focus:ring-red-500">
                                <span class="text-sm font-medium text-slate-700">Eliminar imagen actual</span>
                            </label>
                        </div>
                    @endif
                </div>

                {{-- Acciones --}}
                <div class="pt-4 flex flex-col gap-3">
                    <button type="submit"
                        class="w-full rounded-xl bg-slate-900 py-3.5 text-sm font-bold text-white shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all hover:scale-[1.02]">
                        Actualizar Producto
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
@endsection