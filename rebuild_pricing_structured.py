import sys

create_html = """                {{-- Card: Gestión de Precios Unificado --}}
                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" x-data="{
                                cost: '{{ old('cost_price', 0) }}',
                                tax_percent: '{{ old('taxes_percent', 0) }}',
                                net_cost: 0,

                                // Base Unit Prices
                                base_public_price: '{{ old('public_price', '') }}',
                                base_margin: '',

                                // Additional Units
                                units: {{ json_encode(old('units', [])) }},

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
                                    unit.calculated_cost = (c * (1 + (t / 100))).toFixed(2);
                                    this.updateUnitMargin(unit);
                                },

                                updateUnitMargin(unit) {
                                     let cost = parseFloat(unit.calculated_cost) || 0;
                                     let price = parseFloat(unit.public_price) || 0;
                                     if(cost > 0 && price > 0) {
                                         unit.margin = (((price / cost) - 1) * 100).toFixed(2);
                                     } else {
                                         unit.margin = '';
                                     }
                                },

                                updateUnitPrice(unit) {
                                    let cost = parseFloat(unit.calculated_cost) || 0;
                                    let margin = parseFloat(unit.margin) || 0;
                                    if(cost > 0 && !isNaN(margin)) {
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
                            }">
                    
                    {{-- Header de la Tarjeta --}}
                    <div class="px-8 py-5 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Control de Precios
                        </h2>
                    </div>

                    <div class="p-8 space-y-12">
                        
                        {{-- SECCIÓN 1: COSTO Y VENTA --}}
                        <div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">1. Configuración de Precio Base (Pieza)</h3>
                            
                            <div class="flex flex-col lg:flex-row gap-6">
                                
                                {{-- Bloque de Costo --}}
                                <div class="flex-1 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                                    <div class="flex items-center gap-2 mb-5">
                                        <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                        <h4 class="text-[11px] font-black text-slate-500 uppercase tracking-wider">Estructura de Costo</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                                        {{-- Costo Compra --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Costo Compra</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-slate-400 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" name="cost_price" x-model="cost" class="w-full pl-7 pr-3 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm transition-all" placeholder="0.00">
                                            </div>
                                        </div>

                                        {{-- IVA --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">IVA %</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0" name="taxes_percent" x-model="tax_percent" class="w-full px-4 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm transition-all" placeholder="0">
                                                <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Costo Neto (Label Only) --}}
                                        <div class="pb-1.5 pl-2">
                                            <label class="block text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Costo Neto Calc.</label>
                                            <div class="text-xl font-black text-blue-600 tracking-tight">
                                                <span x-text="'$' + net_cost.toFixed(2)"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Separator Arrow --}}
                                <div class="hidden lg:flex items-center justify-center text-slate-200">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                </div>

                                {{-- Bloque de Venta --}}
                                <div class="flex-[0.8] bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100 shadow-sm">
                                    <div class="flex items-center gap-2 mb-5">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                        <h4 class="text-[11px] font-black text-emerald-700 uppercase tracking-wider">Precio al Público</h4>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-end">
                                        {{-- Margen Base --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Margen</label>
                                            <div class="relative">
                                                <input type="number" step="any" x-model="base_margin" @input="updateBasePrice()" placeholder="Ganancia" class="w-full pl-4 pr-8 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-sm transition-all">
                                                <span class="absolute right-4 top-2.5 text-emerald-400 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Precio Público --}}
                                        <div>
                                            <label class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Precio Venta</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2.5 text-emerald-500 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" name="public_price" x-model="base_public_price" @input="updateBaseMargin()" required class="w-full pl-8 pr-3 py-2.5 rounded-xl border-emerald-400 text-sm font-black text-emerald-800 bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 shadow-md transition-all">

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
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">2. Precios por Presentación</h3>
                                <button type="button" @click="addUnit()" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Agregar Variante
                                </button>
                            </div>

                            <p class="text-sm text-slate-500 mb-6" x-show="units.length === 0">
                                No se han agregado presentaciones adicionales (ej: Caja, Charola, Rollo).
                            </p>

                            {{-- Encabezados de Tabla (Solo visibles en Desktop) --}}
                            <div class="hidden lg:flex items-center gap-4 px-2 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-100 mb-2 pr-14" x-show="units.length > 0">
                                <div class="w-48">Presentación</div>
                                <div class="w-32">Costo Base</div>
                                <div class="w-24">IVA %</div>
                                <div class="w-32 text-blue-600">Neto Calc.</div>
                                <div class="w-28 text-emerald-600">Margen %</div>
                                <div class="w-40 text-emerald-600">Precio Público</div>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(unit, index) in units" :key="index">
                                    <div class="flex flex-col lg:flex-row gap-4 lg:items-center px-4 py-3 lg:px-2 rounded-xl border border-slate-200 lg:border-transparent lg:border-b hover:bg-slate-50 transition-colors relative pr-14 shadow-sm lg:shadow-none">
                                        
                                        <button type="button" @click="removeUnit(index)" class="absolute top-3 right-3 lg:top-1/2 lg:-translate-y-1/2 text-slate-300 hover:text-red-500 transition-colors bg-white hover:bg-red-50 rounded-lg p-1.5 border border-slate-100" title="Eliminar presentación">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>

                                        {{-- Unidad --}}
                                        <div class="w-full lg:w-48">
                                            <label class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Unidad</label>
                                            <select :name="'units['+index+'][unit_id]'" x-model="unit.unit_id" required class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-2 py-2">
                                                <option value="">Selección...</option>
                                                @foreach($units as $u)
                                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Costo Compra --}}
                                        <div class="w-full lg:w-32">
                                            <label class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Costo Base</label>
                                            <div class="relative">
                                                <span class="absolute left-2.5 top-2 text-slate-400 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" :name="'units['+index+'][cost_price]'" x-model="unit.cost_price" @input="updateUnitCost(unit)" required class="w-full pl-6 pr-2 py-2 rounded-lg border-slate-300 text-sm focus:border-blue-500">
                                            </div>
                                        </div>

                                        {{-- IVA --}}
                                        <div class="w-full lg:w-24">
                                            <label class="block lg:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">IVA %</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0" :name="'units['+index+'][taxes_percent]'" x-model="unit.taxes_percent" @input="updateUnitCost(unit)" class="w-full px-3 py-2 rounded-lg border-slate-300 text-sm focus:border-blue-500">
                                            </div>
                                        </div>

                                        {{-- Costo Neto Calc (Text Only) --}}
                                        <div class="w-full lg:w-32 flex flex-col justify-center">
                                            <label class="block lg:hidden text-[10px] font-bold text-blue-500 uppercase mb-1">Neto Calc.</label>
                                            <span class="text-base font-black text-blue-600" x-text="'$' + (unit.calculated_cost || '0.00')"></span>
                                        </div>

                                        {{-- Margen Unit --}}
                                        <div class="w-full lg:w-28">
                                            <label class="block lg:hidden text-[10px] font-bold text-emerald-600 uppercase mb-1">Margen %</label>
                                            <div class="relative">
                                                <input type="number" step="any" x-model="unit.margin" @input="updateUnitPrice(unit)" class="w-full pl-3 pr-7 py-2 rounded-lg border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500">
                                                <span class="absolute right-3 top-2 text-emerald-500 font-bold">%</span>
                                            </div>
                                        </div>

                                        {{-- Precio Presentación --}}
                                        <div class="w-full lg:w-40">
                                            <label class="block lg:hidden text-[10px] font-bold text-emerald-600 uppercase mb-1">Precio Público</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-2 text-emerald-500 font-bold">$</span>
                                                <input type="number" step="0.01" min="0" :name="'units['+index+'][public_price]'" x-model="unit.public_price" @input="updateUnitMargin(unit)" required class="w-full pl-7 pr-2 py-2 rounded-lg border-emerald-400 text-sm font-black text-emerald-800 bg-emerald-50 focus:border-emerald-500 shadow-sm">
                                            </div>

                                            {{-- Hidden fields --}}
                                            <input type="hidden" :name="'units['+index+'][sale_price]'" :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][mid_wholesale_price]'" :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][wholesale_price]'" :value="unit.public_price">
                                            <input type="hidden" :name="'units['+index+'][conversion_factor]'" value="1">
                                            <input type="hidden" :name="'units['+index+'][barcode]'" value="">
                                        </div>

                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>
"""


edit_html = create_html.replace(
    "cost: '{{ old('cost_price', 0) }}'", "cost: '{{ old('cost_price', $product->cost_price) }}'"
).replace(
    "tax_percent: '{{ old('taxes_percent', 0) }}'", "tax_percent: '{{ old('taxes_percent', $product->taxes_percent) }}'"
).replace(
    "base_public_price: '{{ old('public_price', '') }}'", "base_public_price: '{{ old('public_price', $product->public_price) }}'"
).replace(
    "units: {{ json_encode(old('units', [])) }}", "units: {{ json_encode(old('units', $product->units->toArray())) }}"
)


def replace_in_file(filepath, new_block):
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
        
    start_idx = -1
    end_idx = -1
    for i, line in enumerate(lines):
        if '{{-- Card: Gestión de Precios Unificado --}}' in line or '{{-- Card: Gestin de Precios Unificado --}}' in line:
            start_idx = i
            break
            
    if start_idx == -1:
         for i, line in enumerate(lines):
             if '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" x-data="{ ' in line or '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" x-data="{cost:' in line or '<div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden" x-data="{' in line:
                 start_idx = max(0, i-1)
                 break

    for i in range(max(start_idx, 0), len(lines)):
        if '{{-- Card: Imagen --}}' in lines[i] or '{{-- Card: Fotografía --}}' in lines[i]:
            end_idx = i
            break
            
    if start_idx != -1 and end_idx != -1:
        new_lines = lines[:start_idx] + [new_block + "\n"] + lines[end_idx:]
        with open(filepath, 'w', encoding='utf-8') as f:
            f.writelines(new_lines)
        print(f"Success for {filepath}")
    else:
        print(f"Failed to find bounds in {filepath}. Start: {start_idx}, End: {end_idx}")

replace_in_file('resources/views/admin/products/create.blade.php', create_html)
replace_in_file('resources/views/admin/products/edit.blade.php', edit_html)
