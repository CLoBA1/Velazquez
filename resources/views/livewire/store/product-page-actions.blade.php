<div>
    @if($stock > 0)
        <div class="space-y-4">
            <!-- Qty & Add -->
            <div class="flex gap-4">
                <!-- Qty Stepper -->
                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 h-14 w-32 shrink-0">
                    <button wire:click="decrement"
                        class="w-8 h-full text-gray-500 hover:text-orange-600 font-bold transition-colors disabled:opacity-50"
                        @if($quantity <= 1) disabled @endif>-</button>

                    <input type="text" wire:model.live="quantity"
                        class="w-full text-center border-none focus:ring-0 font-bold text-lg text-slate-900 p-0" readonly>

                    <button wire:click="increment"
                        class="w-8 h-full text-gray-500 hover:text-orange-600 font-bold transition-colors disabled:opacity-50"
                        @if($quantity >= $stock) disabled @endif>+</button>
                </div>

                <button wire:click="addToCart" wire:loading.attr="disabled"
                    class="flex-1 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-lg h-14 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="addToCart">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </span>
                    <span wire:loading wire:target="addToCart" class="animate-spin">
                        <svg class="w-5 h-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </span>
                    <span wire:loading.remove wire:target="addToCart">Agregar al Carrito</span>
                    <span wire:loading wire:target="addToCart">Agregando...</span>
                </button>
            </div>

            <button wire:click="buyNow" wire:loading.attr="disabled"
                class="w-full bg-orange-100 hover:bg-orange-200 text-orange-700 font-bold rounded-xl h-12 transition-colors flex items-center justify-center gap-2">
                <span wire:loading wire:target="buyNow" class="animate-spin">
                    <svg class="w-5 h-5 text-orange-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
                <span wire:loading.remove wire:target="buyNow">Comprar Ahora</span>
                <span wire:loading wire:target="buyNow">Procesando...</span>
            </button>
        </div>

        <div class="flex items-center gap-2 text-sm text-green-600 font-medium mt-4">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            En Stock ({{ $stock }} disponibles)
        </div>
    @else
        <div
            class="w-full bg-gray-100 text-gray-500 font-bold rounded-xl h-14 flex items-center justify-center gap-2 cursor-not-allowed">
            Agotado
        </div>
    @endif
</div>