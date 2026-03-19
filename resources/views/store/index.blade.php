@extends('layouts.store')

@section('title', 'Catálogo Premium | Ferretería')

@section('content')

    <!-- Hero Section -->
    <!-- Hero Carousel -->
    <div class="relative bg-dark border-b border-gray-100 overflow-hidden group" x-data="{
                activeSlide: 0,
                slides: [
                    @if($banners->count() > 0)
                        {{-- ✅ Opcion 2: Banners personalizados desde el admin --}}
                        @foreach($banners as $banner)
                            {
                                badge: '{{ $banner->subtitle ?? "BANNER" }}',
                                title_prefix: '',
                                title_highlight: '{{ $banner->title }}',
                                title_gradient: 'from-secondary to-yellow-400',
                                description: '{{ Str::limit($banner->description ?? "", 150) }}',
                                cta_primary: '{{ $banner->label_primary }}',
                                cta_secondary: '{{ $banner->label_secondary ?? "" }}',
                                link_primary: '{{ $banner->link_primary ?? "#" }}',
                                link_secondary: '{{ $banner->link_secondary ?? "#catalogo" }}',
                                badge_style: 'bg-white/10 border-white/20 text-white',
                                btn_primary_style: 'bg-accent hover:bg-red-700 shadow-red-500/30',
                                image: '{{ $banner->image_url ?? asset("img/hero-bg.png") }}'
                            },
                        @endforeach
                    @else
                        {{-- Opcion 1: Slides automaticos desde ofertas/nuevos ingresos --}}
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
                                    badge_style: 'bg-accent/10 border-accent/20 text-accent',
                                    btn_primary_style: 'bg-accent hover:bg-red-700 shadow-red-500/30',
                                    image: '{{ $offer->image_url ?? asset("img/hero-bg.png") }}'
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
                                badge_style: 'bg-secondary/10 border-secondary/20 text-secondary',
                                btn_primary_style: 'bg-primary hover:bg-blue-700 shadow-blue-500/30',
                                image: '{{ asset("img/hero-bg.png") }}'
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
                                badge_style: 'bg-primary/10 border-primary/20 text-primary',
                                btn_primary_style: 'bg-primary hover:bg-blue-700 shadow-blue-600/30',
                                image: '{{ $arrival->image_url ?? asset("img/hero-bg.png") }}'
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
                                badge_style: 'bg-secondary/10 border-secondary/20 text-secondary',
                                btn_primary_style: 'bg-secondary hover:bg-yellow-500 shadow-yellow-500/30 text-dark',
                                image: '{{ asset("img/hero-bg.png") }}'
                            }
                        @endif
                    @endif
                ],
                init() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide === this.slides.length - 1) ? 0 : this.activeSlide + 1;
                    }, 6000);
                }
             }">

        <!-- Background con imagen reactiva - gradiente solo a la izquierda -->
        <div class="absolute inset-0 z-0">
            <!-- Imagen de fondo - sin oscurecer, se ve completa -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
                :style="`background-image: url('${slides[activeSlide]?.image ?? '{{ asset('img/hero-bg.png') }}'}')`">
            </div>
            <!-- Gradiente solo del lado izquierdo: texto legible, imagen luce a la derecha -->
            <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(5,8,18,0.88) 0%, rgba(5,8,18,0.75) 35%, rgba(5,8,18,0.30) 60%, rgba(5,8,18,0.05) 100%);"></div>
        </div>

        <!-- Slides Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 min-h-[520px] flex items-center">
            <!-- Using grid to stack slides on top of each other while maintaining height -->
            <div class="grid grid-cols-1 grid-rows-1 w-full">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index"
                        class="col-start-1 row-start-1 w-full max-w-xl pt-14 pb-20 sm:pt-20 sm:pb-28 text-left"
                        x-transition:enter="transition ease-out duration-700 delay-100"
                        x-transition:enter-start="opacity-0 translate-y-8"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300 absolute"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-8">

                        <!-- Badge - solo si tiene texto -->
                        <template x-if="slide.badge">
                            <span
                                class="inline-flex items-center py-1 px-4 rounded-full border text-xs font-bold tracking-widest mb-5 uppercase backdrop-blur-sm shadow-sm"
                                :class="slide.badge_style" x-text="slide.badge">
                            </span>
                        </template>

                        <!-- Título: usa gradiente solo si hay prefix (slides auto), blanco puro si no -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-5 drop-shadow-lg"
                            :class="slide.title_prefix ? 'text-white' : 'text-white'">
                            <template x-if="slide.title_prefix">
                                <span>
                                    <span class="text-white" x-text="slide.title_prefix"></span><br>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r" :class="slide.title_gradient" x-text="slide.title_highlight"></span>
                                </span>
                            </template>
                            <template x-if="!slide.title_prefix">
                                <span class="text-white leading-tight" x-text="slide.title_highlight"></span>
                            </template>
                        </h1>

                        <template x-if="slide.description">
                            <p class="text-sm sm:text-base text-white/75 mb-8 leading-relaxed max-w-sm font-normal drop-shadow"
                                x-text="slide.description">
                            </p>
                        </template>

                        <div class="flex flex-wrap gap-3">
                            <template x-if="slide.cta_primary">
                                <a :href="slide.link_primary"
                                    class="text-white font-bold py-3.5 px-8 rounded-xl transition-all hover:shadow-2xl hover:-translate-y-1 transform flex items-center gap-2 shadow-lg text-sm"
                                    :class="slide.btn_primary_style">
                                    <span x-text="slide.cta_primary"></span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </template>
                            <template x-if="slide.cta_secondary">
                                <a :href="slide.link_secondary"
                                    class="bg-white/15 hover:bg-white/25 text-white font-semibold py-3.5 px-8 rounded-xl transition-all border border-white/40 hover:border-white/60 text-sm backdrop-blur-sm">
                                    <span x-text="slide.cta_secondary"></span>
                                </a>
                            </template>
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