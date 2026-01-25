@extends('layouts.store')

@section('title', 'Ofertas y Promociones | Ferretería Velázquez')

@section('content')
    <div class="bg-slate-50 min-h-screen pb-20">

        <!-- Hero Section -->
        <div class="bg-slate-900 overflow-hidden relative isolate">
            <!-- Background accents -->
            <div
                class="absolute inset-0 -z-10 bg-[radial-gradient(45rem_50rem_at_top,theme(colors.indigo.500),theme(colors.slate.900))] opacity-20">
            </div>
            <div class="absolute inset-y-0 right-0 -z-10 w-[50%] bg-gradient-to-l from-slate-800/50 to-transparent"></div>

            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-24 relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="max-w-2xl text-center md:text-left animate-fade-in-up">
                    <span
                        class="inline-flex items-center gap-2 rounded-full bg-orange-500/10 px-3 py-1 text-sm font-semibold text-orange-400 ring-1 ring-inset ring-orange-500/20 mb-6">
                        <svg class="w-4 h-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                        </svg>
                        Ofertas Relámpago
                    </span>
                    <h1 class="text-4xl font-black tracking-tight text-white sm:text-6xl mb-6">
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-200">Descuentos</span>
                        que construyen tus sueños
                    </h1>
                    <p class="text-lg leading-8 text-gray-300 mb-8">
                        Aprovecha precios especiales en herramientas profesionales y materiales de construcción por tiempo
                        limitado.
                    </p>
                    <div class="flex items-center justify-center md:justify-start gap-x-6">
                        <a href="#catalogo"
                            class="rounded-xl bg-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-sm hover:bg-orange-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-400 transition-all hover:scale-105">Ver
                            Ofertas</a>
                    </div>
                </div>

                <!-- Floating Decorative Elements -->
                <div class="relative hidden md:block animate-fade-in delay-200">
                    <div
                        class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500 rounded-full blur-3xl opacity-20 animate-pulse">
                    </div>
                    <div
                        class="absolute top-20 -left-10 w-32 h-32 bg-orange-500 rounded-full blur-3xl opacity-20 animate-pulse delay-75">
                    </div>
                    <!-- Example product showcase could go here, for now just a graphic -->
                    <div
                        class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 shadow-2xl skew-y-3 hover:skew-y-0 transition-transform duration-500 cursor-pointer hover:bg-white/10">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-12 h-12 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold text-xl">
                                %</div>
                            <div>
                                <div class="text-white font-bold text-lg">Ahorra hasta</div>
                                <div class="text-orange-400 font-black text-3xl">40% OFF</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offers Grid -->
        <div id="catalogo" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            @if($offers->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($offers as $product)
                        <div
                            class="group bg-white rounded-3xl p-4 transition-all duration-300 hover:shadow-2xl border border-transparent hover:border-orange-100 flex flex-col relative h-full">

                            <!-- Discount Badge -->
                            <div class="absolute top-0 right-0 z-20">
                                <div
                                    class="bg-orange-500 text-white text-xs font-bold px-4 py-2 rounded-bl-3xl rounded-tr-3xl shadow-lg shadow-orange-500/20">
                                    -{{ number_format((1 - $product->sale_price / $product->public_price) * 100, 0) }}%
                                </div>
                            </div>

                            <!-- Image -->
                            <div
                                class="relative aspect-[4/5] bg-gray-50 rounded-2xl overflow-hidden mb-6 flex items-center justify-center p-8 group-hover:bg-orange-50/30 transition-colors">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                                        class="relative z-10 w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500">
                                @else
                                    <div class="text-gray-300">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                <!-- Countdown Overlay if Deadline exists -->
                                @if($product->sale_deadline)
                                    <div
                                        class="absolute bottom-2 left-2 right-2 bg-slate-900/80 backdrop-blur-md rounded-xl p-2 text-center text-white text-xs font-medium z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="block text-[10px] text-slate-300 uppercase tracking-wide">Termina en:</span>
                                        <span x-data="{ 
                                                                        timeLeft: '', 
                                                                        deadline: new Date('{{ $product->sale_deadline->format('Y-m-d\TH:i:s') }}').getTime(),
                                                                        update() {
                                                                            const now = new Date().getTime();
                                                                            const distance = this.deadline - now;
                                                                            if (distance < 0) {
                                                                                this.timeLeft = 'Expirada';
                                                                            } else {
                                                                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                                                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                                                this.timeLeft = `${days}d ${hours}h`;
                                                                            }
                                                                        }
                                                                    }" x-init="update(); setInterval(() => update(), 60000)"
                                            x-text="timeLeft"></span>
                                    </div>
                                @endif
                            </div>

                            <!-- Info -->
                            <div class="flex-1 flex flex-col px-2">
                                <div class="mb-2 flex items-center gap-2">
                                    <span
                                        class="bg-slate-100 text-slate-600 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">{{ $product->brand->name ?? 'Genérico' }}</span>
                                </div>
                                <h3
                                    class="font-bold text-lg text-slate-800 leading-snug mb-2 group-hover:text-orange-600 transition-colors line-clamp-2">
                                    <a href="{{ route('store.show', $product->id) }}">{{ $product->name }}</a>
                                </h3>

                                <div class="mt-auto">
                                    <div class="flex items-end gap-2 mb-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-semibold text-gray-400 line-through">Before
                                                ${{ number_format($product->public_price, 2) }}</span>
                                            <span
                                                class="text-2xl font-black text-slate-900 tracking-tight">${{ number_format($product->sale_price, 2) }}</span>
                                        </div>
                                    </div>

                                    @if($product->stock > 0)
                                        <livewire:store.add-to-cart :productId="$product->id" :key="$product->id" />
                                    @else
                                        <button disabled
                                            class="w-full bg-slate-100 text-slate-400 font-bold py-3 rounded-xl cursor-not-allowed">Agotado</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $offers->links() }}
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-50 mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">No hay ofertas activas por ahora</h2>
                    <p class="text-slate-500">Estamos preparando los mejores descuentos. ¡Vuelve pronto!</p>
                </div>
            @endif
        </div>
    </div>
@endsection