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
                                units: @json(old('units', [])),

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
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </span>
                            Control de Precios
                        </h2>
                        
                        {{-- Resumen de Costos Sticky (Global Base Unit) --}}
                        <div class="flex flex-wrap items-center gap-4 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">C. Compra:</span>
                                <span class="text-sm font-semibold text-slate-800" x-text="cost ? '$' + cost : '$0.00'"></span>
                            </div>
                            <div class="hidden md:block w-px h-5 bg-slate-200"></div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">IVA:</span>
                                <span class="text-sm font-semibold text-slate-800" x-text="tax_percent ? tax_percent + '%' : '0%'"></span>
                            </div>
                            <div class="hidden md:block w-px h-5 bg-slate-200"></div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Costo Neto Base:</span>
                                <span class="text-lg font-black text-blue-700" x-text="'$' + net_cost.toFixed(2)"></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-10">
                        {{-- SECCIÓN 1: DEFINIR COSTOS Y PRECIO BASE --}}
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide border-b border-slate-200 pb-3 mb-6">1. Precio de la Unidad Base <span class="text-slate-400 font-normal normal-case ml-2">(Pieza individual)</span></h3>
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

                                {{-- Costo Compra --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Costo de Compra</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-slate-400 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="cost_price" x-model="cost" class="w-full pl-8 pr-3 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all shadow-sm">
                                    </div>
                                </div>

                                {{-- IVA --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">IVA %</label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0" name="taxes_percent" x-model="tax_percent" class="w-full px-3 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all shadow-sm">
                                        <span class="absolute right-4 top-2.5 text-slate-400 font-bold">%</span>
                                    </div>
                                </div>

                                {{-- Costo Neto (Display) --}}
                                <div>
                                    <label class="block text-xs font-bold text-blue-600 uppercase tracking-wider mb-2">Costo Neto Calc.</label>
                                    <div class="flex items-center h-[42px] px-3 bg-blue-50 rounded-xl border border-blue-100/50">
                                        <span class="text-sm font-bold text-blue-800" x-text="'$' + net_cost.toFixed(2)"></span>
                                    </div>
                                </div>

                                {{-- Margen Base --}}
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2">Margen Ganancia</label>
                                    <div class="relative">
                                        <input type="number" step="any" x-model="base_margin" @input="updateBasePrice()" placeholder="Ej. 30" class="w-full pl-4 pr-8 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all shadow-sm">
                                        <span class="absolute right-4 top-2.5 text-emerald-500 font-bold">%</span>
                                    </div>
                                </div>

                                {{-- Precio Público --}}
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase tracking-wider mb-2">Precio Público</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-2.5 text-emerald-500 font-bold">$</span>
                                        <input type="number" step="0.01" min="0" name="public_price" x-model="base_public_price" @input="updateBaseMargin()" required class="w-full pl-8 pr-3 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all shadow-sm">

                                        {{-- Hidden Fields --}}
                                        <input type="hidden" name="sale_price" :value="base_public_price">
                                        <input type="hidden" name="mid_wholesale_price" :value="base_public_price">
                                        <input type="hidden" name="wholesale_price" :value="base_public_price">
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- SECCIÓN 2: PRESENTACIONES ADICIONALES --}}
                        <div>
                            <div class="flex items-center justify-between border-b border-slate-200 pb-3 mb-6">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wide">2. Presentaciones Adicionales</h3>
                                <button type="button" @click="addUnit()" class="inline-flex items-center gap-1.5 text-sm font-bold text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Nueva Presentación
                                </button>
                            </div>

                            <p class="text-sm text-slate-500 italic mb-4" x-show="units.length === 0">
                                Sin presentaciones adicionales (ej: Caja, Charola).
                            </p>

                            <div class="space-y-4">
                                <template x-for="(unit, index) in units" :key="index">
                                    <div class="p-5 rounded-2xl border border-slate-200 bg-white relative group hover:border-blue-300 transition-all shadow-sm">
                                        
                                        <button type="button" @click="removeUnit(index)" class="absolute -top-3 -right-3 text-slate-400 hover:text-white bg-white hover:bg-red-500 border border-slate-200 hover:border-red-500 rounded-full p-1.5 transition-colors shadow-sm" title="Eliminar presentación">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>

                                        <div class="grid grid-cols-1 lg:grid-cols-6 gap-4 items-start">
                                            
                                            {{-- Unidad --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Unidad</label>
                                                <select :name="'units['+index+'][unit_id]'" x-model="unit.unit_id" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                                    <option value="">Seleccionar...</option>
                                                    @foreach($units as $u)
                                                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Costo Compra (Independent) --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">Costo de Compra</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2.5 text-slate-400 font-bold">$</span>
                                                    <input type="number" step="0.01" min="0" :name="'units['+index+'][cost_price]'" x-model="unit.cost_price" @input="updateUnitCost(unit)" required class="w-full pl-7 pr-2 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm">
                                                </div>
                                            </div>

                                            {{-- IVA (Independent) --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-2">IVA %</label>
                                                <div class="relative">
                                                    <input type="number" step="0.01" min="0" :name="'units['+index+'][taxes_percent]'" x-model="unit.taxes_percent" @input="updateUnitCost(unit)" class="w-full px-3 py-2.5 rounded-xl border-slate-300 text-sm font-semibold text-slate-800 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 shadow-sm">
                                                    <span class="absolute right-3 top-2.5 text-slate-400 font-bold">%</span>
                                                </div>
                                            </div>

                                            {{-- Costo Neto Calculado --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-blue-600 uppercase tracking-wider mb-2">Costo Neto Calc.</label>
                                                <div class="flex items-center h-[42px] px-3 bg-blue-50/80 rounded-xl border border-blue-100/50">
                                                    <span class="text-sm font-bold text-blue-800" x-text="'$' + (unit.calculated_cost || '0.00')"></span>
                                                </div>
                                            </div>

                                            {{-- Margen Unit --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Margen %</label>
                                                <div class="relative">
                                                    <input type="number" step="any" x-model="unit.margin" @input="updateUnitPrice(unit)" class="w-full pl-3 pr-7 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all shadow-sm">
                                                    <span class="absolute right-3 top-2.5 text-emerald-500 font-bold">%</span>
                                                </div>
                                            </div>

                                            {{-- Precio Presentación --}}
                                            <div>
                                                <label class="block text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2">Precio Público</label>
                                                <div class="relative">
                                                    <span class="absolute left-3 top-2.5 text-emerald-500 font-bold">$</span>
                                                    <input type="number" step="0.01" min="0" :name="'units['+index+'][public_price]'" x-model="unit.public_price" @input="updateUnitMargin(unit)" required class="w-full pl-7 pr-2 py-2.5 rounded-xl border-emerald-300 text-sm font-bold text-emerald-700 bg-emerald-50 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all shadow-sm">
                                                </div>

                                                {{-- Hidden fields (including dummy conversion_factor for validation) --}}
                                                <input type="hidden" :name="'units['+index+'][sale_price]'" :value="unit.public_price">
                                                <input type="hidden" :name="'units['+index+'][mid_wholesale_price]'" :value="unit.public_price">
                                                <input type="hidden" :name="'units['+index+'][wholesale_price]'" :value="unit.public_price">
                                                <input type="hidden" :name="'units['+index+'][conversion_factor]'" value="1">
                                                <input type="hidden" :name="'units['+index+'][barcode]'" value="">
                                            </div>

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
    "units: @json(old('units', []))", "units: @json(old('units', $product->units->toArray()))"
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
