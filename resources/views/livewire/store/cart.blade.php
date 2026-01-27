<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-black text-dark mb-8 tracking-tight">Tu Carrito de Compras</h1>

    @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-12">

            <!-- Items List -->
            <div class="flex-1 space-y-6">
                @foreach($cartItems as $id => $item)
                    <div
                        class="flex flex-col sm:flex-row items-center gap-6 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <!-- Image -->
                        <div class="w-full sm:w-24 h-24 bg-gray-50 rounded-xl flex items-center justify-center p-2">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" class="w-full h-full object-contain mix-blend-multiply">
                            @else
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @endif
                        </div>

                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-bold text-dark text-lg leading-tight">{{ $item['name'] }}</h3>
                            <p class="text-gray-500 text-sm mt-1 mb-2 font-mono">${{ number_format($item['price'], 2) }}
                                unitario</p>
                            <button wire:click="removeItem({{ $id }})"
                                class="text-red-500 text-xs font-bold hover:underline">Eliminar</button>
                        </div>

                        <!-- Quantity -->
                        <div class="flex items-center gap-2">
                            <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-slate-600 font-bold flex items-center justify-center transition-colors">-</button>
                            <span class="w-8 text-center font-bold text-slate-900">{{ $item['quantity'] }}</span>
                            <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-slate-600 font-bold flex items-center justify-center transition-colors">+</button>
                        </div>

                        <!-- Total Item -->
                        <div class="text-right min-w-[100px]">
                            <span
                                class="block text-xl font-bold text-dark">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    </div>
                @endforeach

                <div class="text-right">
                    <button wire:click="clearCart" class="text-red-500 font-bold hover:underline text-sm">Vaciar
                        Carrito</button>
                </div>
            </div>

            <!-- Summary -->
            <div class="w-full lg:w-96">
                <div class="bg-gray-50 border border-gray-100 p-8 rounded-3xl sticky top-28">
                    <h3 class="font-bold text-xl text-slate-900 mb-6">Resumen del Pedido</h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Envío</span>
                            <span class="text-green-600 font-bold">Gratis</span> <!-- Logic can be added later -->
                        </div>
                        <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
                            <span class="font-bold text-dark text-lg">Total</span>
                            <span class="font-black text-dark text-3xl">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('store.checkout') }}"
                        class="block w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl text-center shadow-lg shadow-blue-500/30 transition-all hover:scale-[1.02] transform">
                        Proceder al Pago
                    </a>

                    <a href="{{ route('store.index') }}"
                        class="block w-full text-center text-slate-500 hover:text-slate-800 font-bold mt-4 py-2 text-sm">
                        Seguir Comprando
                    </a>
                </div>
            </div>

        </div>
    @else
        <div class="text-center py-24 px-6 bg-white rounded-[2.5rem] border border-dashed border-gray-200">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13L4.707 15.293a1 1 0 00.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-dark mb-4">Tu carrito está vacío</h2>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">Parece que aún no has agregado productos. Explora nuestro
                catálogo y encuentra las mejores herramientas.</p>
            <a href="{{ route('store.index') }}"
                class="inline-flex items-center gap-2 bg-dark text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition-colors shadow-xl shadow-dark/20">
                Ir al Catálogo
            </a>
        </div>
    @endif
</div>