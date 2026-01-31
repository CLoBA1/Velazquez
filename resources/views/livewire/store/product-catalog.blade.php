<div class="min-h-screen">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Sidebar Navigation (Desktop) -->
        <div class="hidden lg:block w-72 flex-shrink-0 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg text-slate-900">Departamentos</h3>
                    @if($category)
                        <button wire:click="setCategory(null)" class="text-xs font-semibold text-red-500 hover:text-red-600">
                            Borrar filtro
                        </button>
                    @endif
                </div>

                <div class="space-y-6">
                    <!-- 'Todos' Link -->
                    <div>
                        <button wire:click="setCategory(null)" 
                            class="flex items-center gap-2 w-full text-left transition-colors {{ is_null($category) ? 'text-primary font-bold' : 'text-slate-600 hover:text-primary' }}">
                            <span class="w-2 h-2 rounded-full {{ is_null($category) ? 'bg-secondary' : 'bg-transparent border border-slate-300' }}"></span>
                            Ver Todos
                        </button>
                    </div>

                    @foreach($families as $family)
                        @if($family->categories->count() > 0)
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 uppercase tracking-wider mb-3 pb-2 border-b border-gray-100">
                                    {{ $family->name }}
                                </h4>
                                <ul class="space-y-2">
                                    @foreach($family->categories as $cat)
                                        <li>
                                            <button wire:click="setCategory({{ $cat->id }})" 
                                                class="flex items-center gap-2 w-full text-left text-sm transition-colors group {{ $category == $cat->id ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary' }}">
                                                <span class="w-1.5 h-1.5 rounded-full transition-colors {{ $category == $cat->id ? 'bg-secondary' : 'bg-slate-200 group-hover:bg-secondary' }}"></span>
                                                {{ $cat->name }}
                                            </button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Mobile Filter Drawer (Optional/Advanced) or Simple Dropdown for Mobile -->
        <div class="lg:hidden mb-6">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <label class="block text-sm font-bold text-slate-700 mb-2">Filtrar por Departamento</label>
                <select wire:change="setCategory($event.target.value)" class="w-full rounded-lg border-gray-200 focus:border-primary focus:ring-primary">
                    <option value="">Todos los departamentos</option>
                    @foreach($families as $family)
                        <optgroup label="{{ $family->name }}">
                            @foreach($family->categories as $cat)
                                <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Main Content (Grid) -->
        <div class="flex-1">
            
            <!-- Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <p class="text-slate-500 text-sm font-medium">
                    Mostrando <span class="text-slate-900 font-bold">{{ $products->count() }}</span> de <span class="text-slate-900 font-bold">{{ $totalProducts }}</span> resultados
                </p>

                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <select wire:model.live="sort" class="w-full sm:w-48 appearance-none pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer transition-all">
                        <option value="recommended">Recomendados</option>
                        <option value="price_asc">Menor Precio</option>
                        <option value="price_desc">Mayor Precio</option>
                        <option value="newest">Nuevos</option>
                    </select>
                </div>
            </div>

            <!-- Product Grid -->
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($products as $product)
                        <div class="group bg-white rounded-2xl p-4 transition-all duration-300 hover:shadow-lg border border-gray-100 hover:border-primary/20 flex flex-col relative h-full" wire:key="product-{{ $product->id }}">
                            
                            <!-- Badges -->
                            <div class="absolute top-4 left-4 z-20 flex flex-col gap-1 pointer-events-none">
                                @if($product->stock <= 0)
                                    <span class="bg-slate-800 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">AGOTADO</span>
                                @elseif($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">OFERTA</span>
                                @endif
                            </div>

                            <!-- Image -->
                            <a href="{{ route('store.show', $product->id) }}" class="block relative aspect-square bg-white rounded-xl overflow-hidden mb-4 p-4 group-hover:bg-gray-50 transition-colors">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-200">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </a>

                            <!-- Info -->
                            <div class="flex-1 flex flex-col">
                                <div class="mb-2">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{{ $product->category->family->name ?? 'General' }}</span>
                                </div>
                                <h3 class="font-bold text-slate-800 leading-snug mb-2 line-clamp-2 group-hover:text-primary transition-colors">
                                    <a href="{{ route('store.show', $product->id) }}">{{ $product->name }}</a>
                                </h3>
                                
                                <div class="mt-auto pt-4 border-t border-gray-50 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        @if($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                            <span class="text-xs text-gray-400 line-through">${{ number_format($product->public_price, 2) }}</span>
                                            <span class="text-lg font-black text-slate-900">${{ number_format($product->sale_price, 2) }}</span>
                                        @else
                                            <span class="text-xs text-transparent select-none">.</span>
                                            <span class="text-lg font-black text-slate-900">${{ number_format($product->public_price, 2) }}</span>
                                        @endif
                                    </div>
                                    <button class="w-10 h-10 rounded-full bg-gray-50 text-slate-600 flex items-center justify-center hover:bg-secondary hover:text-dark transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 text-center">
                    @if($products->hasMorePages())
                        <button wire:click="loadMore" wire:loading.attr="disabled" class="px-8 py-3 bg-white border border-gray-200 hover:border-primary text-slate-700 font-bold rounded-full shadow-sm hover:shadow-md transition-all">
                            <span wire:loading.remove>Cargar más productos</span>
                            <span wire:loading>Cargando...</span>
                        </button>
                    @endif
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-200">
                    <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 mb-2">Sin resultados</h2>
                    <p class="text-slate-500 mb-6">Prueba seleccionando otra categoría o limpiando los filtros.</p>
                    <button wire:click="setCategory(null)" class="text-primary font-bold hover:underline">Ver todos los productos</button>
                </div>
            @endif
        </div>
    </div>
</div>
