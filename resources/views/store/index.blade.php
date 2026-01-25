@extends('layouts.store')

@section('title', 'Catálogo Premium | Ferretería')

@section('content')

<!-- Hero Section -->
<!-- Hero Carousel -->
<div class="relative bg-slate-900 border-b border-gray-100 overflow-hidden group"
     x-data="{
        activeSlide: 0,
        slides: [
            @if(isset($featured_offer))
            {
                badge: 'OFERTA DESTACADA',
                title_prefix: 'Ahorra en',
                title_highlight: '{{ Str::limit($featured_offer->name, 20) }}',
                title_gradient: 'from-orange-400 to-orange-600',
                description: '{{ Str::limit($featured_offer->description ?? "Aprovecha nuestros precios especiales en herramientas y materiales de alta calidad.", 100) }}',
                cta_primary: 'Comprar Ahora',
                cta_secondary: 'Ver Ofertas',
                link_primary: '{{ route("store.show", $featured_offer->id) }}',
                link_secondary: '#catalogo',
                bg_accent: 'bg-orange-600/20',
                badge_style: 'bg-orange-500/10 border-orange-500/20 text-orange-400',
                btn_primary_style: 'bg-orange-500 hover:bg-orange-600 shadow-orange-500/30'
            },
            @else
            {
                badge: 'NUEVA COLECCIÓN 2024',
                title_prefix: 'Maestría en',
                title_highlight: 'Herramientas',
                title_gradient: 'from-orange-400 to-orange-600',
                description: 'Equípate con precisión. Catálogo curado para profesionales que exigen durabilidad, rendimiento y confianza en cada trabajo.',
                cta_primary: 'Comprar Ahora',
                cta_secondary: 'Ver Marcas',
                link_primary: '#catalogo',
                link_secondary: '#',
                bg_accent: 'bg-orange-600/20',
                badge_style: 'bg-orange-500/10 border-orange-500/20 text-orange-400',
                btn_primary_style: 'bg-orange-500 hover:bg-orange-600 shadow-orange-500/30'
            },
            @endif

            @if(isset($new_arrival))
            {
                badge: 'NUEVO INGRESO',
                title_prefix: 'Descubre',
                title_highlight: 'Novedades',
                title_gradient: 'from-blue-400 to-blue-600',
                description: 'Llegó {{ $new_arrival->name }}. Tecnología y calidad superior para tus proyectos más exigentes.',
                cta_primary: 'Ver Nuevo',
                cta_secondary: 'Catálogo',
                link_primary: '{{ route("store.show", $new_arrival->id) }}',
                link_secondary: '#catalogo',
                bg_accent: 'bg-blue-600/20',
                badge_style: 'bg-blue-500/10 border-blue-500/20 text-blue-400',
                btn_primary_style: 'bg-blue-600 hover:bg-blue-700 shadow-blue-600/30'
            },
            @endif

            @if(isset($category_highlight))
            {
                badge: 'EXPLORA',
                title_prefix: 'Todo en',
                title_highlight: '{{ $category_highlight->name }}',
                title_gradient: 'from-yellow-400 to-yellow-500',
                description: 'Encuentra la mejor selección de productos en nuestra categoría de {{ $category_highlight->name }}.',
                cta_primary: 'Explorar',
                cta_secondary: 'Marcas',
                link_primary: '{{ route("store.index", ["category" => $category_highlight->id]) }}',
                link_secondary: '#',
                bg_accent: 'bg-yellow-600/20',
                badge_style: 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400',
                btn_primary_style: 'bg-yellow-500 hover:bg-yellow-600 shadow-yellow-500/30'
            }
            @endif
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
            }, 6000);
        }
     }">
    
    <!-- Dynamic Background -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/95 to-slate-800/90 z-10"></div>
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <template x-for="(slide, index) in slides" :key="index">
             <div x-show="activeSlide === index"
                  x-transition:enter="transition ease-out duration-[1500ms]"
                  x-transition:enter-start="opacity-0 scale-90"
                  x-transition:enter-end="opacity-100 scale-100"
                  x-transition:leave="transition ease-in duration-1000"
                  x-transition:leave-start="opacity-100 scale-100"
                  x-transition:leave-end="opacity-0 scale-110"
                  class="absolute -right-20 -top-40 w-[600px] h-[600px] rounded-full blur-3xl animate-pulse"
                  :class="slide.bg_accent"></div>
        </template>
    </div>
    
    <!-- Slides Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 min-h-[600px] flex items-center">
        <!-- Using grid to stack slides on top of each other while maintaining height -->
        <div class="grid grid-cols-1 grid-rows-1 w-full">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index"
                     class="col-start-1 row-start-1 w-full max-w-3xl pt-16 pb-20 sm:pt-24 sm:pb-32 text-center sm:text-left"
                     x-transition:enter="transition ease-out duration-700 delay-100"
                     x-transition:enter-start="opacity-0 translate-y-8"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-300 absolute"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-8">
                    
                    <span class="inline-block py-1 px-3 rounded-full border text-sm font-bold tracking-wide mb-6 backdrop-blur-sm"
                          :class="slide.badge_style"
                          x-text="slide.badge">
                    </span>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-white tracking-tight leading-none mb-6">
                        <span x-text="slide.title_prefix"></span> <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r"
                              :class="slide.title_gradient"
                              x-text="slide.title_highlight"></span>
                    </h1>
                    
                    <p class="text-lg sm:text-xl text-slate-300 mb-10 leading-relaxed max-w-xl font-light mx-auto sm:mx-0"
                       x-text="slide.description">
                    </p>
                    
                    <div class="flex flex-wrap gap-4 justify-center sm:justify-start">
                        <a :href="slide.link_primary" 
                           class="text-white font-bold py-4 px-8 rounded-2xl transition-all hover:shadow-xl hover:-translate-y-1 transform flex items-center gap-2"
                           :class="slide.btn_primary_style">
                            <span x-text="slide.cta_primary"></span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a :href="slide.link_secondary" class="bg-white/5 hover:bg-white/10 text-white backdrop-blur-md font-semibold py-4 px-8 rounded-2xl transition-all border border-white/10 hover:border-white/20">
                            <span x-text="slide.cta_secondary"></span>
                        </a>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Navigation Dots -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 flex gap-3 z-30">
        <template x-for="(slide, index) in slides" :key="index">
            <button @click="activeSlide = index" 
                    class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
                    :class="activeSlide === index ? 'w-10 bg-white' : 'w-2 bg-white/20 hover:bg-white/40'">
            </button>
        </template>
    </div>
</div>

<!-- Main Content Area (Full Width Layout) -->
<div id="catalogo" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
    
    <!-- 1. Categories Top Bar (Horizontal Scroll Pills) -->
    <div class="mb-12 animate-fade-in delay-100" x-data="{
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
            <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                Departamentos
            </h3>
            <!-- Scroll Controls -->
            <div class="hidden md:flex gap-2">
                <button @click="scrollLeft()" class="p-2 rounded-full bg-white border border-gray-200 text-slate-500 hover:text-orange-500 hover:border-orange-200 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button @click="scrollRight()" class="p-2 rounded-full bg-white border border-gray-200 text-slate-500 hover:text-orange-500 hover:border-orange-200 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
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
            <a href="{{ route('store.index') }}" 
               class="flex-shrink-0 snap-start px-6 py-3 rounded-full font-bold transition-all duration-200 border border-transparent whitespace-nowrap {{ !request('category') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-white text-slate-600 border-gray-200 hover:border-orange-200 hover:text-orange-600' }}">
                Todos
            </a>
            
            @foreach($categories as $category)
                <a href="{{ route('store.index', array_merge(request()->all(), ['category' => $category->id, 'page' => null])) }}" 
                   class="flex-shrink-0 snap-start px-6 py-3 rounded-full font-bold transition-all duration-200 border whitespace-nowrap {{ request('category') == $category->id ? 'bg-orange-500 text-white border-orange-500 shadow-lg shadow-orange-500/30' : 'bg-white text-slate-600 border-gray-200 hover:border-orange-200 hover:text-orange-600 hover:bg-orange-50' }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- 2. Product Grid (Full Width) -->
    <div class="animate-fade-in delay-200">
        
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4 bg-gray-50/50 p-2 rounded-2xl border border-gray-100">
            <p class="text-slate-500 text-sm font-medium pl-2">
                Mostrando <span class="text-slate-900 font-bold text-lg">{{ $products->count() }}</span> resultados
            </p>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select onchange="window.location.href=this.value" class="w-full sm:w-48 appearance-none pl-4 pr-10 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-slate-700 hover:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-500/20 cursor-pointer shadow-sm transition-all">
                    <option value="{{ route('store.index', array_merge(request()->except('sort'), ['sort' => 'recommended'])) }}" {{ request('sort') == 'recommended' ? 'selected' : '' }}>Recomendados</option>
                    <option value="{{ route('store.index', array_merge(request()->except('sort'), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Menor Precio</option>
                    <option value="{{ route('store.index', array_merge(request()->except('sort'), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Mayor Precio</option>
                    <option value="{{ route('store.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Nuevos</option>
                </select>
            </div>
        </div>

        @if($products->count() > 0)
            <!-- Grid Adjusted: 1 col mobile, 2 cols sm, 3 cols lg, 4 cols xl -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
                @foreach($products as $loop_idx => $product)
                    <div class="group bg-white rounded-[2rem] p-4 transition-all duration-500 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] hover:-translate-y-2 border border-transparent hover:border-orange-100 flex flex-col relative h-full animate-fade-in-up" 
                         style="animation-delay: {{ $loop_idx * 50 }}ms">
                        
                        <!-- Badges -->
                        <div class="absolute top-6 left-6 z-20 flex flex-col gap-2 pointer-events-none">
                            @if($product->stock <= 0)
                                 <span class="bg-slate-800 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-slate-900/30 uppercase tracking-wider backdrop-blur-md">Agotado</span>
                            @elseif($product->stock <= 5)
                                <span class="bg-red-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-red-500/30 uppercase tracking-wider backdrop-blur-md">Últimos</span>
                            @elseif($product->created_at->diffInDays(now()) < 30)
                                <span class="bg-blue-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-blue-600/30 uppercase tracking-wider backdrop-blur-md">Nuevo</span>
                            @endif
                            @if($product->stock > 0 && $product->sale_price > 0 && $product->sale_price < $product->public_price)
                                 <span class="bg-orange-500 text-white text-[10px] font-bold px-3 py-1.5 rounded-full shadow-lg shadow-orange-500/30 uppercase tracking-wider backdrop-blur-md">Oferta</span>
                            @endif
                        </div>

                        <!-- Actions Hover -->
                        <div class="absolute top-5 right-5 z-20 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-4 group-hover:translate-x-0">
                            <button class="p-3 bg-white text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-full shadow-xl border border-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>

                        <!-- Image -->
                        <a href="{{ route('store.show', $product->id) }}" class="block relative aspect-[4/5] bg-gray-50 rounded-[1.5rem] overflow-hidden mb-6 flex items-center justify-center p-8 transition-colors group-hover:bg-gray-50/50">
                            <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-0"></div>
                             @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="relative z-10 w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700 ease-out">
                            @else
                                <div class="relative z-10 text-gray-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </a>

                        <!-- Info -->
                        <div class="flex-1 flex flex-col px-2 pb-2">
                             <div class="mb-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-md bg-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $product->category->name ?? 'General' }}</span>
                            </div>
                            <h3 class="font-bold text-lg text-slate-800 leading-snug mb-3 group-hover:text-orange-600 transition-colors duration-300 line-clamp-2 min-h-[3.25rem]">
                                <a href="{{ route('store.show', $product->id) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <div class="mt-auto flex items-center justify-between">
                                <div class="flex flex-col">
                                    @if($product->sale_price > 0 && $product->sale_price < $product->public_price)
                                        <span class="text-xs font-semibold text-gray-400 line-through mb-0.5">${{ number_format($product->public_price * 1.1, 0) }}</span>
                                        <span class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                    @else
                                        <span class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->public_price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @if($product->stock > 0)
                                    <livewire:store.add-to-cart :productId="$product->id" :key="$product->id" />
                                @else
                                    <button disabled 
                                            class="bg-slate-200 text-slate-400 w-12 h-12 rounded-full flex items-center justify-center cursor-not-allowed"
                                            title="Agotado">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-20">
                {{ $products->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="text-center py-24 px-6 bg-white rounded-[2.5rem] border border-dashed border-gray-200 animate-fade-in-up">
                <div class="bg-orange-50 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 mb-4 tracking-tight">Sin resultados</h2>
                <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition-colors shadow-xl shadow-slate-900/20">
                    Limpiar Filtros
                </a>
            </div>
        @endif
    </div>
</div>

@endsection
