<div>
    @if($stock > 0)
        <div class="space-y-4">
            <!-- Qty & Add -->
            <div class="flex gap-4">
                <!-- Qty Stepper -->
                <div class="flex items-center bg-white border border-gray-300 rounded-xl px-3 h-14 w-32 shrink-0">
                    <button wire:click="decrement"
                        class="w-8 h-full text-gray-500 hover:text-primary font-bold transition-colors disabled:opacity-50"
                        @if($quantity <= 1) disabled @endif>-</button>

                    <input type="text" wire:model.live="quantity"
                        class="w-full text-center border-none focus:ring-0 font-bold text-lg text-dark p-0" readonly>

                    <button wire:click="increment"
                        class="w-8 h-full text-gray-500 hover:text-primary font-bold transition-colors disabled:opacity-50"
                        @if($quantity >= $stock) disabled @endif>+</button>
                </div>

                <button wire:click="addToCart" wire:loading.attr="disabled"
                    class="flex-1 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl text-lg h-14 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2">
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
                class="w-full bg-secondary hover:bg-yellow-500 text-dark font-bold rounded-xl h-12 transition-colors flex items-center justify-center gap-2">
                <span wire:loading wire:target="buyNow" class="animate-spin">
                    <svg class="w-5 h-5 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
                <span wire:loading.remove wire:target="buyNow">Comprar Ahora</span>
                <span wire:loading wire:target="buyNow">Procesando...</span>
            </button>

            <a href="https://wa.me/527447491902?text={{ urlencode('Hola, me interesa cotizar el producto: ' . $product->name . ' (ID: ' . $product->id . ')') }}"
                target="_blank"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl h-12 transition-colors flex items-center justify-center gap-2 mt-4">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.355-5.298c0-5.457 4.432-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                </svg>
                Cotizar por WhatsApp
            </a>
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