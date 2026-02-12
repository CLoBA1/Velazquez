<div class="relative w-full max-w-2xl mx-auto" x-data="{ open: false }" @click.away="open = false">
    <div class="relative">
        <input 
            type="text" 
            wire:model.live.debounce.300ms="query"
            @focus="open = true"
            @input="open = true"
            placeholder="Buscar productos, marcas..."
            class="w-full pl-6 pr-14 py-3 bg-gray-50 border border-gray-200 rounded-full text-base focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none shadow-sm"
            autocomplete="off"
        >
        <div class="absolute right-2 top-2 flex items-center gap-1">
            <button type="button" @click="$dispatch('open-scanner')" class="p-1.5 bg-gray-100 text-gray-500 rounded-full hover:bg-gray-200 hover:text-primary transition-colors" title="Escanear">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
            </button>
            <button class="p-1.5 bg-primary text-white rounded-full hover:bg-blue-800 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </div>
    </div>

    @if(strlen($query) >= 2)
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-2"
            class="absolute top-full left-0 w-full mt-2 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50"
            style="display: none;"
        >
            @if(count($results) > 0)
                <div class="py-2">
                    <div class="px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Productos encontrados</div>
                    @foreach($results as $product)
                        <a href="{{ route('store.show', $product) }}" class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                            <div class="h-10 w-10 bg-white rounded-lg border border-gray-100 flex items-center justify-center p-1">
                                @if($product->main_image_path)
                                    <img src="{{ Storage::url($product->main_image_path) }}" alt="{{ $product->name }}" class="h-full w-full object-contain">
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate group-hover:text-primary transition-colors">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $product->brand->name ?? 'Sin marca' }}</p>
                            </div>
                            <div class="text-sm font-bold text-secondary">
                                ${{ number_format($product->public_price, 2) }}
                            </div>
                        </a>
                    @endforeach
                    <a href="{{ route('store.index', ['search' => $query]) }}" class="block text-center py-3 text-sm font-bold text-primary hover:bg-blue-50 transition-colors">
                        Ver todos los resultados para "{{ $query }}"
                    </a>
                </div>
            @else
                <div class="p-8 text-center text-gray-500">
                    <p>No encontramos productos que coincidan.</p>
                </div>
            @endif
        </div>
    @endif

    <x-scanner-modal @scan-completed.window="$wire.set('query', $event.detail.code)" />
</div>
