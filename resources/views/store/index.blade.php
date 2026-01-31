@extends('layouts.store')

@section('title', 'Catálogo Premium | Ferretería')

@section('content')

    <!-- Hero Section -->
    <!-- Hero Carousel -->
    <div class="relative bg-dark border-b border-gray-100 overflow-hidden group" x-data="{
                activeSlide: 0,
                slides: [
                    @if($featured_offers->count() > 0)
                        @foreach($featured_offers as $offer)
                            {
                                badge: 'OFERTA DESTACADA',
                                title_prefix: 'Ahorra en',
                                title_highlight: '{{ Str::limit($offer->name, 20) }}',
                                title_gradient: 'from-accent to-red-600',
                                description: '{{ Str::limit($offer->description ?? "Aprovecha nuestros precios especiales en herramientas de alta calidad.", 100) }}',
                                cta_primary: 'Comprar Ahora',
                                cta_secondary: 'Ver Ofertas',
                                link_primary: '{{ route("store.show", $offer->id) }}',
                                link_secondary: '{{ route("store.offers.index") }}',
                                bg_accent: 'bg-accent/20',
                                badge_style: 'bg-accent/10 border-accent/20 text-accent',
                                btn_primary_style: 'bg-accent hover:bg-red-700 shadow-red-500/30'
                            },
                        @endforeach
                    @else
                        {
                            badge: 'NUEVA COLECCIÓN 2024',
                            title_prefix: 'Maestría en',
                            title_highlight: 'Herramientas',
                            title_gradient: 'from-secondary to-yellow-500',
                            description: 'Equípate con precisión. Catálogo curado para profesionales que exigen durabilidad, rendimiento y confianza en cada trabajo.',
                            cta_primary: 'Comprar Ahora',
                            cta_secondary: 'Ver Marcas',
                            link_primary: '#catalogo',
                            link_secondary: '{{ route("store.brands.index") }}',
                            bg_accent: 'bg-secondary/20',
                            badge_style: 'bg-secondary/10 border-secondary/20 text-secondary',
                            btn_primary_style: 'bg-primary hover:bg-blue-700 shadow-blue-500/30'
                        },
                    @endif

                    @foreach($new_arrivals as $arrival)
                        {
                            badge: 'NUEVO INGRESO',
                            title_prefix: 'Descubre',
                            title_highlight: 'Novedades',
                            title_gradient: 'from-blue-400 to-primary',
                            description: 'Llegó {{ Str::limit($arrival->name, 30) }}. Tecnología y calidad superior para tus proyectos más exigentes.',
                            cta_primary: 'Ver Nuevo',
                            cta_secondary: 'Catálogo',
                            link_primary: '{{ route("store.show", $arrival->id) }}',
                            link_secondary: '#catalogo',
                            bg_accent: 'bg-primary/20',
                            badge_style: 'bg-primary/10 border-primary/20 text-primary',
                            btn_primary_style: 'bg-primary hover:bg-blue-700 shadow-blue-600/30'
                        },
                    @endforeach

                    @if(isset($category_highlight))
                        {
                            badge: 'EXPLORA',
                            title_prefix: 'Todo en',
                            title_highlight: '{{ $category_highlight->name }}',
                            title_gradient: 'from-secondary to-yellow-400',
                            description: 'Encuentra la mejor selección de productos en nuestra categoría de {{ $category_highlight->name }}.',
                            cta_primary: 'Explorar',
                            cta_secondary: 'Marcas',
                            link_primary: '{{ route("store.index", ["category" => $category_highlight->id]) }}',
                            link_secondary: '{{ route("store.brands.index") }}',
                            bg_accent: 'bg-secondary/20',
                            badge_style: 'bg-secondary/10 border-secondary/20 text-secondary',
                            btn_primary_style: 'bg-secondary hover:bg-yellow-500 shadow-yellow-500/30 text-dark'
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
        <!-- Dynamic Background with Image -->
        <div class="absolute inset-0 z-0">
            <!-- Background Image -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat transition-transform duration-[10000ms] ease-linear transform hover:scale-105"
                style="background-image: url('{{ asset('img/hero-bg.png') }}');">
            </div>

            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-dark via-gray-900/90 to-black/80 z-10"></div>

            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-20 z-10"
                style="background-image: radial-gradient(#FFF5E1 1px, transparent 1px); background-size: 32px 32px;">
            </div>
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

                        <span
                            class="inline-block py-1 px-3 rounded-full border text-sm font-bold tracking-wide mb-6 backdrop-blur-sm"
                            :class="slide.badge_style" x-text="slide.badge">
                        </span>

                        <h1 class="text-3xl sm:text-4xl lg:text-6xl font-black text-white tracking-tight leading-none mb-6">
                            <span x-text="slide.title_prefix"></span> <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r" :class="slide.title_gradient"
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
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                            <a :href="slide.link_secondary"
                                class="bg-white/5 hover:bg-white/10 text-white backdrop-blur-md font-semibold py-4 px-8 rounded-2xl transition-all border border-white/10 hover:border-white/20">
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
                <button @click="activeSlide = index" class="h-1.5 rounded-full transition-all duration-300 shadow-sm"
                    :class="activeSlide === index ? 'w-10 bg-white' : 'w-2 bg-white/20 hover:bg-white/40'">
                </button>
            </template>
        </div>
    </div>

    <!-- Main Content Area (Full Width Layout) -->
    <div id="catalogo" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
        <livewire:store.product-catalog business-line="hardware" />
    </div>

@endsection