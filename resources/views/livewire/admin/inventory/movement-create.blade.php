<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-dark tracking-tight">Registrar Nuevo Ajuste</h1>
            <p class="text-slate-500 text-sm mt-0.5">Realiza movimientos manuales de inventario.</p>
        </div>
        <a href="{{ route('admin.inventory.movements') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Volver al Historial
        </a>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- Product Search -->
            <div class="relative">
                <label class="block text-sm font-bold text-slate-700 mb-2">Buscar Producto <span
                        class="text-red-500">*</span></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="pl-10 block w-full rounded-xl border-slate-200 py-3 text-sm focus:border-primary focus:ring-primary disabled:bg-slate-50 disabled:text-slate-500 transition-all shadow-sm"
                        placeholder="Escribe el nombre, código o SKU..." {{ $productId ? 'readonly' : '' }}>

                    @if($productId)
                        <button type="button" wire:click="$set('productId', null); $set('search', '')"
                            class="absolute right-3 top-2.5 p-1 rounded-full text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                @if(!empty($results) && !$productId)
                    <div
                        class="absolute z-50 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl max-h-72 overflow-y-auto ring-1 ring-black ring-opacity-5">
                        <ul class="divide-y divide-slate-100">
                            @foreach($results as $product)
                                <li class="p-3 hover:bg-slate-50 cursor-pointer transition-colors group"
                                    wire:click="selectProduct({{ $product->id }})">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="font-bold text-dark group-hover:text-primary transition-colors">
                                                {{ $product->name }}
                                            </div>
                                            <div class="text-xs text-slate-500 font-mono mt-0.5">
                                                Code: {{ $product->internal_code }}
                                                @if($product->supplier_sku) | SKU: {{ $product->supplier_sku }} @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-slate-500">Stock Actual</div>
                                            <div
                                                class="font-bold {{ $product->stock <= $product->min_stock ? 'text-red-600' : 'text-emerald-600' }}">
                                                {{ $product->stock }} {{ $product->unit->symbol ?? '' }}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @error('productId') <p class="mt-1 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg> Selecciona un producto de la lista.</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Type -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tipo de Movimiento <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model="type"
                            class="block w-full rounded-xl border-slate-200 py-3 pl-3 pr-10 text-sm focus:border-primary focus:ring-primary transition-all shadow-sm">
                            <option value="adjustment_add" class="font-bold">➕ Ajuste de Entrada (Positivo)</option>
                            <option value="adjustment_sub" class="font-bold">➖ Ajuste de Salida (Negativo)</option>
                            <option disabled class="text-slate-300">──────────</option>
                            <option value="purchase">📦 Compra (Entrada)</option>
                            <option value="return">↩️ Devolución (Entrada)</option>
                            <option value="sale">💰 Venta Manual (Salida)</option>
                        </select>
                    </div>
                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Cantidad <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" step="0.01" wire:model="quantity"
                            class="block w-full rounded-xl border-slate-200 py-3 text-sm focus:border-primary focus:ring-primary transition-all shadow-sm font-mono font-bold"
                            placeholder="0.00">
                    </div>
                    @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Notas / Razón</label>
                <textarea wire:model="notes" rows="3"
                    class="block w-full rounded-xl border-slate-200 py-3 text-sm focus:border-primary focus:ring-primary transition-all shadow-sm"
                    placeholder="Describe por qué se realiza este ajuste (ej. daño en almacén, conteo cíclico incorrecto, etc.)"></textarea>
                @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.inventory.movements') }}"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition-all">
                    Cancelar
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Registrar Movimiento
                </button>
            </div>
        </form>
    </div>
</div>