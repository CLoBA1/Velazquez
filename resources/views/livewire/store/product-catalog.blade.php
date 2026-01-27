<div class="min-h-screen">

    <!-- Categories Filter (Horizontal Scroll) -->
    <div class="mb-12 animate-fade-in delay-100 p-6 rounded-3xl bg-gradient-to-r from-dark to-slate-900 shadow-xl shadow-dark/10"
        x-data="{
        scrollContainer: null,
        init() {
            this.scrollContainer = this.$refs.container;
        },
        scrollLeft() {
            this.scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
        },
        scrollRight() {
            this.scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
        }
    }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-white text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7">
                    </path>
                </svg>
                Departamentos
            </h3>
            <!-- Scroll Controls -->
            <div class="hidden md:flex gap-2">
                <button @click="scrollLeft()"
                    class="p-2 rounded-full bg-white/10 border border-white/10 text-white hover:bg-secondary hover:text-dark transition-all shadow-sm backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                </button>
                <button @click="scrollRight()"
                    class="p-2 rounded-full bg-white/10 border border-white/10 text-white hover:bg-secondary hover:text-dark transition-all shadow-sm backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div x-ref="container"
            class="flex overflow-x-auto pb-4 gap-3 hide-scrollbar snap-x snap-mandatory cursor-grab active:cursor-grabbing"
            @mousedown="
                let isDown = true;
                let startX = $event.pageX - $el.offsetLeft;
                let scrollLeft = $el.scrollLeft;
                $el.addEventListener('mouseup', () => isDown = false);
                $el.addEventListener('mouseleave', () => isDown = false);
                $el.addEventListener('mousemove', (e) => {
                    if(!isDown) return;
                    e.preventDefault();
                    const x = e.pageX - $el.offsetLeft;
                    const walk = (x - startX) * 2; // scroll-fast
                    $el.scrollLeft = scrollLeft - walk;
                });
             ">
            <!-- 'All' Pill -->
            <button wire:click="setCategory(null)"
                class="flex-shrink-0 snap-start px-6 py-3 rounded-full font-bold transition-all duration-200 border border-transparent whitespace-nowrap {{ is_null($category) ? 'bg-secondary text-dark shadow-lg shadow-yellow-500/20' : 'bg-white/10 text-slate-300 border-white/5 hover:border-white/20 hover:bg-white/20 hover:text-white backdrop-blur-sm' }}">
                Todos
            </button>

            @foreach($categories as $cat)
                <button wire:click="setCategory({{ $cat->id }})"
                    class="flex-shrink-0 snap-start px-6 py-3 rounded-full font-bold transition-all duration-200 border whitespace-nowrap {{ $category == $cat->id ? 'bg-secondary text-dark border-secondary shadow-lg shadow-yellow-500/20' : 'bg-white/10 text-slate-300 border-white/5 hover:border-white/20 hover:bg-white/20 hover:text-white backdrop-blur-sm' }}">
                    {{ $cat->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Toolbar & Results Count -->
    <div
        class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 bg-gray-50/50 p-2 rounded-2xl border border-gray-100">
        <p class="text-slate-500 text-sm font-medium pl-2">
            Mostrando <span class="text-slate-900 font-bold text-lg">{{ $products->count() }}</span> de <span
                class="text-slate-900 font-bold text-lg">{{ $totalProducts }}</span> resultados
        </p>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select wire:model.live="sort"
                class="w-full sm:w-48 appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 cursor-pointer shadow-sm transition-all">
                <option value="recommended">Recomendados</option>
                <option value="price_asc">Menor Precio</option>
                <option value="price_desc">Mayor Precio</option>
                <option value="newest">Nuevos</option>
            </select>
        </div>
    </div>

    <!-- Product Grid or Empty State -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
            @foreach($products as $product)
                <div class="group bg-white rounded-[2rem] p-4 transition-all duration-500 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 border border-transparent hover:border-blue-100 flex flex-col relative h-full animate-fade-in-up"
                    wire:key="product-{{ $product->id }}">

                    <!-- Badges -->
                    <div class="absolute top-6 left-6 z-20 flex flex-col gap-2 pointer-events-none">
                        @if($product->stock <= 0)
                            <span
                                class="bg-slate-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-slate-900/30 uppercase tracking-wider backdrop-blur-md">Agotado</span>
                        @elseif($product->stock <= 5)
                            <span
                                class="bg-red-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30 uppercase tracking-wider backdrop-blur-md">Últimos</span>
                        @elseif($product->created_at->diffInDays(now()) < 30)
                            <span
                                class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-blue-600/30 uppercase tracking-wider backdrop-blur-md">Nuevo</span>
                        @endif
                        @if($product->stock > 0 && $product->sale_price > 0 && $product->sale_price < $product->public_price)
                            <span
                                class="bg-accent text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30 uppercase tracking-wider backdrop-blur-md">Oferta</span>
                        @endif
                    </div>

                    <!-- Actions Hover -->
                    <div
                        class="absolute top-5 right-5 z-20 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                        <button
                            class="p-3 bg-white text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full shadow-xl border border-gray-100 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Image -->
                    <a href="{{ route('store.show', $product->id) }}"
                        class="block relative aspect-[4/5] bg-gray-50 rounded-[1.5rem] overflow-hidden mb-6 flex items-center justify-center p-8 transition-colors group-hover:bg-gray-50/50">
                        <div
                            class="absolute inset-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0">
                        </div>
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                class="relative z-10 w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700 ease-out">
                        @else
                            <div class="relative z-10 text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </a>

                    <!-- Info -->
                    <div class="flex-1 flex flex-col px-2 pb-2">
                        <div class="mb-3">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $product->category->name ?? 'General' }}</span>
                        </div>
                        <h3
                            class="font-bold text-lg text-dark leading-snug mb-3 group-hover:text-primary transition-colors duration-300 line-clamp-2 min-h-[3.25rem]">
                            <a href="{{ route('store.show', $product->id) }}">
                                {{ $product->name }}
                            </a>
                        </h3>

                        <div class="mt-auto flex items-center justify-between">
                            <div class="flex flex-col">
                                @if($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                    <span
                                        class="text-xs font-semibold text-gray-400 line-through mb-0.5">${{ number_format($product->public_price * 1.1, 0) }}</span>
                                    <span
                                        class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                @else
                                    <span class="text-xs font-semibold text-gray-400 mb-0.5">Precio</span>
                                    <span
                                        class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                @endif
                            </div>
                            <button
                                class="w-12 h-12 rounded-full bg-gray-100 text-dark flex items-center justify-center hover:bg-secondary hover:text-dark transition-all duration-300 shadow-sm group-hover:shadow-md group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Load More Button -->
        <div class="mt-12 text-center">
            @if($products->hasMorePages())
                <button wire:click="loadMore" wire:loading.attr="disabled"
                    class="group relative px-8 py-4 bg-white hover:bg-secondary text-dark font-bold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <span wire:loading.remove class="flex items-center gap-2">
                        Cargar más productos
                        <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7"></path>
                        </svg>
                    </span>
                    <span wire:loading class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-dark" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Cargando...
                    </span>
                </button>
            @else
                <p class="text-gray-400 font-medium">Has llegado al final del catálogo</p>
            @endif
        </div>

    @else
        <!-- Empty State -->
        <div
            class="text-center py-24 px-6 bg-white rounded-[2.5rem] border border-dashed border-gray-200 animate-fade-in-up">
            <div class="bg-gray-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-dark mb-4 tracking-tight">Sin resultados</h2>
            <p class="text-slate-500 mb-8">No encontramos productos en esta categoría. Intenta ajustar los filtros.</p>
            <button wire:click="setCategory(null)"
                class="inline-flex items-center gap-2 bg-dark text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition-colors shadow-xl shadow-dark/20">
                Limpiar Filtros
            </button>
        </div>
    @endif
</div>