<div class="h-[calc(100vh-6rem)] flex flex-col md:flex-row gap-6 font-sans">
    
    <!-- Left Panel: Product Search & Grid -->
    <div class="w-full md:w-3/4 flex flex-col gap-4">
        <!-- Search Bar -->
        <div class="bg-[#0f172a] p-4 rounded-2xl shadow-lg border border-slate-700">
            <div class="relative flex gap-2">
                <div class="relative flex-1">
                    <input wire:model.live.debounce.300ms="search" 
                           id="posSearchInput"
                           type="text" 
                           placeholder="Escanea el código o busca por nombre..."
                           class="w-full pl-12 pr-4 py-4 rounded-xl border-0 bg-slate-800 text-white placeholder-slate-400 focus:ring-2 focus:ring-secondary text-lg shadow-inner font-medium transition-all"
                           autofocus>
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <span class="text-xs text-slate-500 bg-slate-900 px-2 py-1 rounded border border-slate-700">ENTER para Agregar</span>
                    </div>
                </div>
                <button @click="$dispatch('open-scanner')" class="bg-slate-800 border border-slate-700 text-secondary p-4 rounded-xl shadow-lg hover:bg-slate-700 hover:text-white transition-colors flex items-center justify-center flex-shrink-0" title="Escanear Código">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </button>
            </div>
        </div>


        <!-- Product Grid -->
        <div class="flex-1 overflow-y-auto bg-slate-100 rounded-2xl border border-slate-200 p-4 shadow-inner">
            @if(strlen($search) > 1)
                @if(count($products) > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4">
                        @foreach($products as $product)
                            <button wire:click="addToCart({{ $product->id }})" 
                                    class="group flex flex-col bg-white rounded-xl shadow-sm border border-slate-200 hover:border-primary hover:ring-2 hover:ring-primary/20 hover:shadow-xl transition-all p-3 text-left relative overflow-hidden h-full">
                                
                                @if($product->stock <= 0)
                                    <div class="absolute inset-0 bg-white/80 z-20 flex items-center justify-center backdrop-blur-[1px]">
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm font-bold shadow-sm border border-red-200 transform -rotate-12">AGOTADO</span>
                                    </div>
                                @endif

                                <div class="aspect-square bg-slate-50 rounded-lg mb-3 overflow-hidden relative border border-slate-100">
                                     @if($product->image_url)
                                        <img src="{{ $product->image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                     @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                     @endif
                                     <div class="absolute top-2 right-2 bg-slate-900/80 backdrop-blur text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm">
                                         {{ $product->internal_code }}
                                     </div>
                                </div>
                                
                                <div class="flex-1 min-w-0 flex flex-col">
                                    <h3 class="font-bold text-slate-800 text-sm leading-snug mb-1 line-clamp-2 group-hover:text-secondary transition-colors">{{ $product->name }}</h3>
                                    
                                    <div class="mt-auto flex justify-between items-end pt-2 border-t border-slate-50">
                                        <div>
                                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-wider">Precio</span>
                                            <span class="font-black text-lg text-slate-800">${{ number_format($product->public_price, 2) }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="block text-[10px] text-slate-400 font-medium uppercase tracking-wider">Stock</span>
                                            <span class="text-xs font-bold px-2 py-0.5 rounded {{ $product->stock > 5 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-slate-400">
                        <div class="w-24 h-24 bg-slate-200 rounded-full flex items-center justify-center mb-4 opacity-50">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-lg font-medium text-slate-500">No se encontraron productos.</p>
                        <p class="text-sm">Intenta buscar por otro nombre o código.</p>
                    </div>
                @endif
            @else
                <div class="h-full flex flex-col items-center justify-center text-slate-400">
                    <div class="w-32 h-32 bg-slate-200 rounded-full flex items-center justify-center mb-6 opacity-50 animate-pulse">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </div>
                    <p class="text-xl font-medium text-slate-600">Listo para vender</p>
                    <p class="text-sm mt-2">Escanea un código de barras o escribe para buscar.</p>
                </div>
            @endif
        </div>

        <!-- Dynamic Promotions Carousel -->
    <div class="h-24 bg-[#0f172a] rounded-2xl flex items-center px-8 relative overflow-hidden shadow-lg border border-slate-800"
         x-data="{
            activeSlide: 0,
            slides: [
                {
                    title: 'MAESTRÍA EN <span class=\'text-transparent bg-clip-text bg-gradient-to-r from-secondary to-yellow-600\'>HERRAMIENTAS</span>',
                    subtitle: 'Descuentos exclusivos en marca Truper',
                    badge: '-20% OFF'
                },
                {
                    title: 'TEMPORADA DE <span class=\'text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600\'>PINTURAS</span>',
                    subtitle: 'Impermeabilizantes y exteriores al mejor precio',
                    badge: '3x2'
                },
                {
                    title: 'ILUMINACIÓN <span class=\'text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-yellow-600\'>LED</span>',
                    subtitle: 'Ahorra energía con nuestra nueva línea',
                    badge: 'NUEVO'
                }
            ],
            init() {
                setInterval(() => {
                    this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                }, 5000);
            }
         }">
        
        <!-- Slides -->
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="activeSlide === index"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform translate-x-8"
                 x-transition:enter-end="opacity-100 transform translate-x-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-x-0"
                 x-transition:leave-end="opacity-0 transform -translate-x-8"
                 class="absolute inset-0 flex items-center justify-between px-8 w-full">
                
                <div class="z-10">
                    <h3 class="text-2xl font-black italic tracking-wide text-white" x-html="slide.title"></h3>
                    <p class="text-slate-400 text-xs mt-1" x-text="slide.subtitle"></p>
                </div>
                
                <div class="relative z-10 flex flex-col items-end">
                    <div class="bg-secondary text-primary font-black text-xl px-4 py-2 rounded-lg shadow-lg rotate-3 transition-transform duration-300"
                         :class="{'bg-blue-600': index === 1, 'bg-yellow-500': index === 2, 'bg-secondary': index === 0}">
                        <span x-text="slide.badge"></span>
                    </div>
                    <span class="text-[10px] text-slate-500 mt-2 font-medium cursor-pointer hover:text-secondary transition-colors">Ver detalles -></span>
                </div>
            </div>
        </template>

        <!-- Indicators -->
        <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex gap-1.5 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        class="h-1 rounded-full transition-all duration-300"
                        :class="activeSlide === index ? 'w-6 bg-secondary' : 'w-2 bg-slate-700'">
                </button>
            </template>
        </div>
        
        <!-- Background Elements -->
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-slate-800/50 to-transparent pointer-events-none"></div>
    </div>

    </div>

    <!-- Right Panel: Cart & Checkout -->
    <div class="w-full md:w-1/4 bg-white rounded-2xl shadow-xl flex flex-col border border-slate-200 h-full overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
             <div class="w-10 h-10 bg-[#0f172a] rounded-lg flex items-center justify-center text-secondary shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
             </div>
            <div>
                <h2 class="font-bold text-lg text-slate-800 leading-tight">Carrito</h2>
                <p class="text-xs text-slate-500 font-medium">{{ count($cart) }} productos</p>
            </div>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 bg-white">
            @forelse($cart as $id => $item)
                <div class="flex gap-3 relative group py-2 border-b border-slate-50 last:border-0">
                    <div class="w-14 h-14 bg-slate-50 rounded-lg border border-slate-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                         @if($item['image'])
                            <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                         @else
                            <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                         @endif
                    </div>
                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div class="flex justify-between items-start gap-2">
                             <h4 class="font-bold text-slate-700 text-xs leading-tight line-clamp-2" title="{{ $item['name'] }}">{{ $item['name'] }}</h4>
                             <button wire:click="removeFromCart({{ $id }})" class="text-slate-300 hover:text-red-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                             </button>
                        </div>
                        
                        <div class="flex items-center justify-between mt-1">
                            <span class="font-bold text-slate-800 text-sm">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            
                            <div class="flex items-center bg-slate-100 rounded-lg h-7">
                                <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] - 1 }})" class="w-7 h-full flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-red-500 rounded-l-lg transition-colors font-bold text-sm">-</button>
                                <span class="text-xs font-bold text-slate-800 w-6 text-center">{{ $item['quantity'] }}</span>
                                <button wire:click="updateQuantity({{ $id }}, {{ $item['quantity'] + 1 }})" class="w-7 h-full flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-green-600 rounded-r-lg transition-colors font-bold text-sm">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-slate-300 p-8 text-center opacity-60">
                    <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="font-medium text-sm">Tu carrito está vacío</p>
                </div>
            @endforelse
        </div>

        <div class="bg-slate-50 p-4 border-t border-slate-200">
             <!-- Client Selection -->
            <div class="mb-4">
                <div class="flex gap-2 mb-2">
                    <select wire:model.live="selected_client_id" class="flex-1 bg-white border border-slate-200 text-slate-700 text-xs rounded-lg focus:ring-primary focus:border-primary block p-2.5 font-bold">
                        <option value="">Público General</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    <button wire:click="openCreateClientModal" class="bg-[#0f172a] hover:bg-slate-800 text-white rounded-lg px-3 transition-colors shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </div>

                @if($selected_client_id && $client = $clients->find($selected_client_id))
                    <div class="p-2.5 bg-blue-50 border border-blue-100 rounded-lg mb-2">
                        <div class="flex justify-between items-center text-xs mb-1">
                            <span class="text-blue-800 font-bold">Crédito Disp:</span>
                            <span class="font-black {{ $client->available_credit < 0 ? 'text-red-500' : 'text-emerald-600' }}">
                                ${{ number_format($client->available_credit, 2) }}
                            </span>
                        </div>
                         @if($client->available_credit < 0)
                            <div class="text-[10px] text-red-600 font-bold flex items-center gap-1 mt-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                Saldo Vencido: ${{ number_format(abs($client->available_credit), 2) }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>



            <!-- Totals -->
            <div class="space-y-1">
                <div class="flex justify-between text-xs font-bold text-slate-500">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-end border-t border-slate-200 pt-3 mt-2">
                    <span class="text-sm font-bold text-slate-800">Total a Pagar</span>
                    <span class="text-3xl font-black text-[#0f172a] leading-none">${{ number_format($total, 2) }}</span>
                </div>
            </div>

            @error('payment')
                <div class="p-3 rounded-xl bg-red-50 border border-red-100 text-red-600 text-xs font-bold">
                    {{ $message }}
                </div>
            @enderror

            <!-- Pay Button -->
            <button wire:click="openPaymentModal" 
                    @if(empty($cart)) disabled @endif
                    class="w-full mt-4 bg-secondary hover:bg-yellow-400 disabled:bg-slate-300 disabled:cursor-not-allowed text-primary font-black py-4 rounded-xl shadow-lg shadow-yellow-500/30 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 text-lg">
                <span>COBRAR</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </button>
        </div>
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-90 transition-opacity" aria-hidden="true"></div>

                <div class="relative inline-block bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                    Completar Venta
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                                        <p class="text-sm text-slate-500 font-bold uppercase">Total a Pagar</p>
                                        <p class="text-3xl font-black text-[#0f172a]">${{ number_format($total, 2) }}</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Método de Pago</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <button wire:click="$set('paymentMethod', 'cash')" 
                                                    class="px-4 py-3 rounded-xl border {{ $paymentMethod === 'cash' ? 'bg-[#0f172a] text-white border-[#0f172a] shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} font-bold text-sm transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                Efectivo
                                            </button>
                                            <button wire:click="$set('paymentMethod', 'card')" 
                                                    class="px-4 py-3 rounded-xl border {{ $paymentMethod === 'card' ? 'bg-[#0f172a] text-white border-[#0f172a] shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} font-bold text-sm transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                                Tarjeta
                                            </button>
                                            <button wire:click="$set('paymentMethod', 'transfer')" 
                                                    class="px-4 py-3 rounded-xl border {{ $paymentMethod === 'transfer' ? 'bg-[#0f172a] text-white border-[#0f172a] shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }} font-bold text-sm transition-all flex items-center justify-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                                Transferencia
                                            </button>
                                            @php
                                                $creditDisabled = false;
                                                $creditTitle = '';
                                                if (!$selected_client_id) {
                                                    $creditDisabled = true;
                                                    $creditTitle = 'Seleccione un cliente';
                                                } elseif ($client = $clients->find($selected_client_id)) {
                                                    if ($client->available_credit < $total) {
                                                        $creditDisabled = true;
                                                        $creditTitle = 'Crédito insuficiente';
                                                    }
                                                }
                                            @endphp
                                            <button wire:click="$set('paymentMethod', 'credit')" 
                                                    @if($creditDisabled) disabled @endif
                                                    class="px-4 py-3 rounded-xl border {{ $paymentMethod === 'credit' ? 'bg-[#0f172a] text-white border-[#0f172a] shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border-slate-200' }} {{ $creditDisabled ? 'opacity-50 cursor-not-allowed' : 'hover:bg-slate-50' }} font-bold text-sm transition-all text-center flex flex-col items-center justify-center h-full relative overflow-hidden"
                                                    title="{{ $creditTitle }}">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span>Crédito</span>
                                                </div>
                                                @if($creditDisabled && isset($client))
                                                    <span class="text-[10px] font-normal opacity-75 mt-1">Disp: ${{ number_format($client->available_credit, 2) }}</span>
                                                @endif
                                            </button>
                                        </div>
                                        
                                        @if($creditDisabled && isset($client) && $selected_client_id)
                                            <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-lg text-xs text-red-800">
                                                <div class="flex items-center gap-2 mb-1 font-bold">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    {{ $client->available_credit < 0 ? '¡Saldo Vencido!' : 'Crédito Insuficiente' }}
                                                </div>
                                                <p>Faltante: <strong class="text-red-900">${{ number_format($total - $client->available_credit, 2) }}</strong></p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($paymentMethod === 'cash')
                                        <div>
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Monto Recibido</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-3 text-slate-400 font-bold">$</span>
                                                <input type="number" step="0.01" wire:model.live="amountPaid" 
                                                       class="w-full pl-8 pr-4 py-3 rounded-xl border-slate-200 focus:border-secondary focus:ring-secondary font-bold text-lg"
                                                       placeholder="0.00">
                                            </div>
                                            @if($change >= 0)
                                                <div class="mt-3 p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex justify-between items-center">
                                                    <span class="text-sm font-bold text-emerald-800">Cambio a entregar:</span>
                                                    <span class="text-xl font-black text-emerald-600">${{ number_format($change, 2) }}</span>
                                                </div>
                                            @else
                                                <div class="mt-2 text-xs text-red-500 font-bold text-right flex items-center justify-end gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Monto insuficiente
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    @if($paymentMethod === 'credit' && $selected_client_id)
                                        @php $c = $clients->find($selected_client_id); @endphp
                                        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl text-sm">
                                            <p class="font-bold text-[#0f172a] mb-2 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                                Resumen de cuenta
                                            </p>
                                            <div class="space-y-1">
                                                <div class="flex justify-between text-slate-500">
                                                    <span>Crédito actual:</span>
                                                    <span class="font-bold">${{ number_format($c->available_credit, 2) }}</span>
                                                </div>
                                                <div class="flex justify-between text-slate-800 font-bold pt-2 border-t border-slate-200">
                                                    <span>Saldo final:</span>
                                                    <span class="{{ ($c->available_credit - $total) < 0 ? 'text-red-600' : 'text-emerald-600' }}">${{ number_format($c->available_credit - $total, 2) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3 border-t border-slate-100">
                        <button wire:click="finalizeSale" 
                                @if($paymentMethod === 'cash' && $change < 0) disabled @endif
                                type="button" 
                                class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-lg shadow-yellow-500/20 px-4 py-3 bg-secondary text-base font-bold text-primary hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all active:scale-[0.98]">
                            Confirmar Venta
                        </button>
                        <button wire:click="$set('showPaymentModal', false)" type="button" 
                                class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:w-auto sm:text-sm transition-all">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Create Client Modal -->
    @if($showCreateClientModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="modal-title">
                            Nuevo Cliente
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input wire:model="newClient.name" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('newClient.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input wire:model="newClient.email" type="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('newClient.email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                    <input wire:model="newClient.phone" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">RFC</label>
                                    <input wire:model="newClient.rfc" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dirección</label>
                                <input wire:model="newClient.address" type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Límite de Crédito</label>
                                <input wire:model="newClient.credit_limit" type="number" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('newClient.credit_limit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button wire:click="saveClient" type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar
                        </button>
                        <button wire:click="closeCreateClientModal" type="button" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
    <x-scanner-modal />

    @script
    <script>
        window.addEventListener('global-scan-completed', event => {
            console.log('POS Global Scan Received:', event.detail.code);
            const code = event.detail.code;
            
            // Option 1: Direct Wire Set
            $wire.set('search', code);
            
            // Option 2: Fallback to DOM input (visual feedback)
            const input = document.getElementById('posSearchInput');
            if (input) {
                input.value = code;
                input.dispatchEvent(new Event('input', { bubbles: true }));
                input.focus();
            }
        });
    </script>
    @endscript
</div>
