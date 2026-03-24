@extends('admin.layouts.app')

@section('title', 'Editar Material')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Editar Material</h1>
                <p class="text-slate-500 mt-1">Actualizando: {{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.construction.index') }}"
                class="px-4 py-2 bg-white border rounded-lg text-sm font-bold text-slate-700 hover:bg-slate-50">Cancelar</a>
        </div>

        <form action="{{ route('admin.construction.update', $product) }}" method="POST" enctype="multipart/form-data" 
              class="space-y-6" x-data="constructionForm()">
            @csrf
            @method('PUT')
            
            <!-- Type Selector -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                 <h2 class="text-lg font-bold text-slate-900 mb-4">Tipo de Material</h2>
                 <div class="flex flex-col sm:flex-row gap-4">
                     <label class="flex-1 relative cursor-pointer">
                         <input type="radio" name="material_type" value="weight" x-model="material_type" class="peer sr-only">
                         <div class="p-4 rounded-xl border-2 peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:bg-slate-50 transition-all flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                             </div>
                             <div>
                                 <div class="font-bold text-slate-800">Se vende por Peso</div>
                                 <div class="text-xs text-slate-500">Cemento, Mortero, Arena (Ton / Kg)</div>
                             </div>
                         </div>
                     </label>
                     <label class="flex-1 relative cursor-pointer">
                         <input type="radio" name="material_type" value="piece" x-model="material_type" class="peer sr-only">
                         <div class="p-4 rounded-xl border-2 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-slate-50 transition-all flex items-center gap-3">
                             <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                             </div>
                             <div>
                                 <div class="font-bold text-slate-800">Se vende por Pieza</div>
                                 <div class="text-xs text-slate-500">Boquilla, Herramientas (Cajas / Pzas)</div>
                             </div>
                         </div>
                     </label>
                 </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Common info -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre del Material</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Código Interno / SKU</label>
                    <input type="text" name="internal_code" value="{{ old('internal_code', $product->internal_code) }}"
                        required class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500"
                        readonly>
                    <p class="text-xs text-slate-400 mt-1">No editable</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Descripción (Opcional)</label>
                    <textarea name="description" rows="3"
                        class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <!-- POR PESO CONFIGURATION -->
            <div x-show="material_type === 'weight'" x-transition class="space-y-6">
                <div class="bg-orange-50/50 p-6 rounded-2xl border border-orange-100 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-3 pb-2 border-b border-orange-100">
                        <h3 class="font-black text-orange-800 uppercase tracking-wider text-sm flex items-center gap-2">
                            Configuración de Precios y Equivalencias
                        </h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Peso del Bulto (Kg)</label>
                        <div class="relative">
                            <input type="number" step="0.01" name="weight_per_bulto" x-model.number="weight_per_bulto" :required="material_type === 'weight'"
                                class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                            <span class="absolute right-4 top-2 text-slate-400 font-bold">Kg</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold" x-show="weight_per_bulto > 0">
                            Equivale a <span x-text="Math.round(1000 / weight_per_bulto)"></span> bultos por Tonelada
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Precio por Bulto</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                            <input type="number" step="0.50" name="price_bulto" x-model.number="price_bulto" :required="material_type === 'weight'"
                                class="pl-7 w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500 font-bold text-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Precio por Tonelada</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                            <input type="number" step="0.50" name="price_tonelada" x-model.number="price_tonelada" :required="material_type === 'weight'"
                                class="pl-7 w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500 font-bold text-lg">
                        </div>
                    </div>

                    <div class="md:col-span-3 bg-white p-4 rounded-xl border border-slate-200 flex justify-between items-center mt-2">
                        <div class="text-sm font-bold text-slate-600">Precio Base por Kilo Calculado:</div>
                        <div class="text-xl font-black text-orange-600">
                            $<span x-text="price_kilo.toFixed(2)"></span>
                        </div>
                        <!-- Hidden input to send base kilo price -->
                        <input type="hidden" name="public_price" :value="price_kilo.toFixed(2)">
                    </div>
                </div>

                <!-- STOCK POR PESO -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 class="font-bold text-slate-800 mb-4">Actualizar Existencia (Opcional)</h3>
                    <div class="flex gap-4 items-start">
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Cantidad</label>
                            <input type="number" step="any" x-model.number="stock_input"
                                class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500 text-lg font-bold">
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Unidad de Medida</label>
                            <select x-model="stock_unit"
                                class="w-full rounded-xl border-slate-200 focus:ring-orange-500 focus:border-orange-500">
                                <option value="ton">Toneladas</option>
                                <option value="bulto">Bultos</option>
                                <option value="kg">Kilos</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm flex gap-6" x-show="stock_input > 0">
                        <div class="flex-1">
                            <span class="text-slate-500 font-bold text-xs uppercase">Almacenando en BD:</span><br>
                            <span class="font-black text-slate-800" x-text="calculated_kilos.toLocaleString() + ' Kg'"></span>
                        </div>
                        <div class="flex-1 text-right">
                            <span class="text-slate-500 font-bold text-xs uppercase">Equivalente a:</span><br>
                            <span class="font-bold text-orange-600" x-text="calculated_bultos + ' Bultos'"></span>
                        </div>
                    </div>
                    <!-- Real stock field sent to backend -->
                    <input type="hidden" name="stock" :value="calculated_kilos">
                </div>
            </div>

            <!-- POR PIEZA CONFIGURATION -->
            <div x-show="material_type === 'piece'" x-transition class="space-y-6">
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Precio Público</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-slate-400">$</span>
                            <input type="number" step="0.50" name="public_price_piece" x-model.number="price_piece"
                                class="pl-7 w-full rounded-xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 font-bold text-lg">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Stock Actual (Piezas)</label>
                        <input type="number" name="stock_piece" x-model.number="stock_piece"
                            class="w-full rounded-xl border-slate-200 focus:ring-blue-500 focus:border-blue-500 text-lg">
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <label class="block text-sm font-bold text-slate-700 mb-2">Imagen del Material</label>
                @if($product->main_image_path)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $product->main_image_path) }}"
                            class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                @endif
                <input type="file" name="image"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <button type="submit"
                class="w-full py-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transition-all">
                Actualizar Material
            </button>
        </form>
    </div>

    <script>
        function constructionForm() {
            return {
                material_type: '{{ $materialType }}',
                
                // Weight variables
                weight_per_bulto: {{ $weightPerBulto }},
                price_bulto: {{ $priceBulto }},
                price_tonelada: {{ $priceTonelada }},
                stock_input: {{ $materialType === 'weight' ? $product->stock / 1000 : 0 }},
                stock_unit: 'ton', // display backend stock scaled as tons natively
                
                // Piece variables
                price_piece: {{ $materialType === 'piece' ? $product->public_price : 0 }},
                stock_piece: {{ $materialType === 'piece' ? $product->stock : 0 }},

                get price_kilo() {
                    if (!this.weight_per_bulto) return 0;
                    return this.price_bulto / this.weight_per_bulto;
                },

                get calculated_kilos() {
                    if (this.stock_input == null || this.stock_input === '') return 0;
                    if (this.stock_unit === 'ton') return this.stock_input * 1000;
                    if (this.stock_unit === 'bulto') return this.stock_input * this.weight_per_bulto;
                    return this.stock_input; // kg
                },

                get calculated_bultos() {
                    if (!this.weight_per_bulto) return 0;
                    return Math.round((this.calculated_kilos / this.weight_per_bulto) * 100) / 100;
                },

                init() {
                    let firstRun = true;
                    this.$watch('price_bulto', (newVal) => {
                        // Avoid overwriting price_tonelada on initial load
                        if (firstRun) {
                            firstRun = false;
                            return;
                        }
                        if (this.weight_per_bulto) {
                            let bagsPerTon = 1000 / this.weight_per_bulto;
                            this.price_tonelada = Math.round(newVal * bagsPerTon);
                        }
                    });
                    
                    // Trigger first watch setup without evaluating change early
                    setTimeout(() => firstRun = false, 100);
                }
            }
        }
    </script>
@endsection