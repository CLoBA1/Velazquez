<div>
    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar producto..."
        class="w-full rounded-xl border-slate-200 py-2.5 px-4 text-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition-all outline-none"
        autofocus>

    @if(!empty($search))
        <div class="mt-4 space-y-2 max-h-64 overflow-y-auto">
            @forelse($products as $product)
                <button type="button" wire:click="selectProduct({{ $product->id }})"
                    class="w-full text-left p-2 hover:bg-slate-50 rounded-lg flex items-center gap-3 transition-colors group">
                    <div
                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-white border border-transparent group-hover:border-indigo-100">
                        <!-- Tiny Image or Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-bold text-slate-800">{{ $product->name }}</div>
                        <div class="text-xs text-slate-500">
                            ${{ number_format($product->public_price, 2) }}
                        </div>
                    </div>
                </button>
            @empty
                <div class="text-center text-sm text-slate-400 py-4">
                    No se encontraron productos.
                </div>
            @endforelse
        </div>
    @endif
</div>