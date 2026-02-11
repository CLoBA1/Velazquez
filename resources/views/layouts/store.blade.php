<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Ferretería Premium')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Smooth Entry Animations */
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        /* Custom Scrollbar for horizontal scrolling */
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

</head>

<body
    x-data="{ authModal: {{ $errors->any() ? 'true' : 'false' }}, activeTab: '{{ $errors->has('name') || $errors->has('username') || $errors->has('email') ? 'register' : 'login' }}' }"
    class="bg-white text-dark antialiased flex flex-col min-h-screen overflow-x-hidden selection:bg-secondary selection:text-dark">

    <!-- Navbar -->
    <nav x-data="{ open: false, searchOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 10)"
        class="sticky top-0 z-40 transition-all duration-300 w-full"
        :class="{ 'bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-100': scrolled, 'bg-transparent': !scrolled }">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">

                <!-- Logo -->
                <div class="flex items-center gap-4">
                    <button @click="open = !open"
                        class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Logo (Explicitly hidden by controller) -->
                    @if(!isset($hide_header_brand) || !$hide_header_brand)
                        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                            <img src="{{ asset('images/logo-final.png') }}" alt="Ferretería Velázquez"
                                class="h-12 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
                            <div class="flex flex-col">
                                <span class="font-bold text-xl text-slate-900 leading-none">Ferretería
                                    Velazquez</span>
                                @if(isset($product) && $product->business_line === 'construction')
                                    <span
                                        class="text-[10px] uppercase tracking-wide text-blue-600 font-bold">Construcción</span>
                                @elseif(request()->routeIs('construction.*'))
                                    <span
                                        class="text-[10px] uppercase tracking-wide text-blue-600 font-bold">Construcción</span>
                                @elseif(request()->routeIs('machinery.*'))
                                    <span
                                        class="text-[10px] uppercase tracking-wide text-yellow-600 font-bold">Maquinaria</span>
                                @else
                                    <span class="text-[10px] uppercase tracking-wide text-secondary font-bold">Ferretería</span>
                                @endif
                            </div>
                        </a>
                    @else
                        <!-- Placeholder to keep spacing if needed, or just empty -->
                        <div class="h-12"></div>
                    @endif
                </div>

                <!-- Contextual Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    @if(request()->routeIs('home'))
                        <!-- Home: Keep it clean or show main sections? User implied adapting to 'part', so home stays clean -->
                    @elseif(request()->routeIs('construction.*') || (isset($product) && $product->business_line === 'construction'))
                        <a href="{{ route('construction.index') }}" class="text-sm font-bold text-blue-600">Catálogo
                            Materiales</a>
                        <a href="{{ route('home') }}"
                            class="text-sm font-medium text-slate-500 hover:text-slate-800">Inicio</a>
                    @elseif(request()->routeIs('machinery.*'))
                        <a href="{{ route('machinery.index') }}" class="text-sm font-bold text-yellow-600">Catálogo
                            Renta</a>
                        <a href="{{ route('home') }}"
                            class="text-sm font-medium text-slate-500 hover:text-slate-800">Inicio</a>
                    @else
                        <!-- Default to Hardware (store.*) -->
                        <a href="{{ route('store.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('store.index') || (isset($product) && $product->business_line === 'hardware') ? 'text-secondary font-bold' : 'text-slate-600 hover:text-secondary' }} transition-colors">
                            Catálogo
                        </a>
                        <a href="{{ route('store.brands.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('store.brands.*') ? 'text-secondary font-bold' : 'text-slate-600 hover:text-secondary' }} transition-colors">
                            Marcas
                        </a>
                        <a href="{{ route('store.offers.index') }}"
                            class="text-sm font-medium {{ request()->routeIs('store.offers.*') ? 'text-secondary font-bold' : 'text-slate-600 hover:text-secondary' }} transition-colors">
                            Ofertas
                        </a>
                    @endif
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    @if(!request()->routeIs('home'))
                        <button @click="searchOpen = !searchOpen"
                            class="p-2.5 text-slate-600 hover:text-secondary bg-slate-50 hover:bg-orange-50 rounded-full transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    @endif

                    @auth
                        <div x-data="{ openProfile: false }" class="relative">
                            <button @click="openProfile = !openProfile"
                                class="hidden sm:flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-orange-600 transition-colors px-4 py-2 bg-slate-50 hover:bg-orange-50 rounded-full border border-slate-100 hover:border-orange-100">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7">
                                    </path>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="openProfile" @click.away="openProfile = false" style="display: none;"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">

                                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-primary">
                                        Admin Dashboard
                                    </a>
                                @endif

                                <!-- Aquí iría 'Mis Pedidos', etc para clientes -->
                                <a href="{{ route('sales.my-purchases') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-primary">Mis
                                    Compras</a>
                                <a href="{{ route('rentals.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-primary">Mis
                                    Rentas</a>

                                <form method="POST" action="{{ route('logout') }}">
                                    <!-- Usando ruta logout de breeze, o admin.logout -->
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <button @click="authModal = true; activeTab = 'login'"
                            class="hidden sm:flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-primary transition-colors px-4 py-2 bg-gray-50 hover:bg-blue-50 rounded-full border border-gray-100 hover:border-blue-100">
                            <span>Iniciar Sesión</span>
                        </button>
                        <button @click="authModal = true; activeTab = 'register'"
                            class="hidden sm:flex items-center gap-2 text-sm font-bold text-white bg-primary hover:bg-blue-800 transition-colors px-4 py-2 rounded-full shadow-lg shadow-blue-900/20">
                            <span>Registro</span>
                        </button>
                    @endauth



                    <livewire:store.navbar-cart />
                </div>
            </div>
        </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2" @click.away="open = false" style="display: none;"
            class="md:hidden bg-white border-t border-slate-100 absolute w-full left-0 z-30 shadow-lg top-20">
            <div class="px-4 py-6 space-y-4">
                @if(request()->routeIs('home'))
                    <!-- Home Mobile Menu -->
                @elseif(request()->routeIs('construction.*') || (isset($product) && $product->business_line === 'construction'))
                    <a href="{{ route('construction.index') }}"
                        class="block text-base font-bold text-blue-600 hover:text-blue-800 transition-colors">
                        Catálogo Materiales
                    </a>
                    <a href="{{ route('home') }}"
                        class="block text-base font-medium text-slate-500 hover:text-slate-800 transition-colors">
                        Inicio
                    </a>
                @elseif(request()->routeIs('machinery.*'))
                    <a href="{{ route('machinery.index') }}"
                        class="block text-base font-bold text-yellow-600 hover:text-yellow-800 transition-colors">
                        Catálogo Renta
                    </a>
                    <a href="{{ route('home') }}"
                        class="block text-base font-medium text-slate-500 hover:text-slate-800 transition-colors">
                        Inicio
                    </a>
                @else
                    <!-- Default Hardware Mobile Menu -->
                    <a href="{{ route('store.index') }}"
                        class="block text-base font-medium {{ request()->routeIs('store.index') || (isset($product) && $product->business_line === 'hardware') ? 'text-orange-600 font-bold' : 'text-slate-700 hover:text-orange-600' }} transition-colors">
                        Catálogo
                    </a>
                    <a href="{{ route('store.brands.index') }}"
                        class="block text-base font-medium {{ request()->routeIs('store.brands.*') ? 'text-orange-600 font-bold' : 'text-slate-700 hover:text-orange-600' }} transition-colors">
                        Marcas
                    </a>
                    <a href="{{ route('store.offers.index') }}"
                        class="block text-base font-medium {{ request()->routeIs('store.offers.*') ? 'text-orange-600 font-bold' : 'text-slate-700 hover:text-orange-600' }} transition-colors">
                        Ofertas
                    </a>
                @endif

                <!-- Mobile Auth Section -->
                <div class="border-t border-gray-100 pt-6 mt-2 space-y-4">
                    @auth
                        <div class="px-3 py-3 bg-blue-50/50 rounded-xl border border-blue-100">
                            <span class="block text-sm font-bold text-gray-800">Hola, {{ auth()->user()->name }}</span>
                            <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                        </div>

                        @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                            <a href="{{ route('admin.dashboard') }}"
                                class="flex items-center gap-2 text-base font-medium text-gray-700 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                    </path>
                                </svg>
                                Panel Administrativo
                            </a>
                        @endif

                        <a href="{{ route('sales.my-purchases') }}"
                            class="flex items-center gap-2 text-base font-medium text-gray-700 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Mis Compras
                        </a>

                        <a href="{{ route('rentals.index') }}"
                            class="flex items-center gap-2 text-base font-medium text-gray-700 hover:text-primary transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Mis Rentas
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 w-full text-left text-base font-medium text-accent hover:bg-red-50 p-2 -ml-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    @else
                        <div class="grid grid-cols-2 gap-3">
                            <button @click="authModal = true; activeTab = 'login'"
                                class="flex items-center justify-center py-3 rounded-xl bg-white border border-gray-200 text-gray-700 font-bold hover:bg-gray-50 transition-colors shadow-sm">
                                Entrar
                            </button>
                            <button @click="authModal = true; activeTab = 'register'"
                                class="flex items-center justify-center py-3 rounded-xl bg-primary text-white font-bold hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-colors">
                                Registrarse
                            </button>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Search Overlay -->
        <div x-show="searchOpen" @click.away="searchOpen = false" style="display: none;"
            class="absolute top-full left-0 w-full bg-white border-y border-gray-100 shadow-xl py-6 px-4 z-40">
            <livewire:store.search />
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- Corrected Footer -->
    <footer class="bg-dark text-white pt-16 pb-8 border-t border-gray-800 mt-auto w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Grid Layout Fix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="bg-white/10 p-2 rounded-lg">
                            <svg class="h-6 w-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-lg leading-none">Ferretería Velázquez</span>
                            <span class="text-[10px] uppercase text-gray-400">Materiales para Construcción</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">Calidad y confianza en cada herramienta.</p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Tienda</h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="{{ route('store.index') }}" class="hover:text-secondary transition-colors">Catálogo
                                Completo</a></li>
                        <li><a href="{{ route('store.offers.index') }}"
                                class="hover:text-secondary transition-colors">Ofertas del Mes</a></li>
                        <li><a href="{{ route('store.index', ['sort' => 'newest']) }}"
                                class="hover:text-secondary transition-colors">Nuevos Productos</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div class="space-y-4">

                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Ayuda</h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li><a href="{{ route('pages.shipping') }}"
                                class="hover:text-secondary transition-colors">Envíos</a></li>
                        <li><a href="{{ route('pages.returns') }}"
                                class="hover:text-secondary transition-colors">Devoluciones</a></li>
                        <li><a href="{{ route('pages.contact') }}"
                                class="hover:text-secondary transition-colors">Contacto</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Suscríbete</h3>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col gap-2">
                        @csrf
                        <input type="email" name="email" placeholder="Email" required
                            class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white w-full focus:ring-1 focus:ring-primary outline-none">
                        <button type="submit"
                            class="bg-primary hover:bg-blue-600 text-white font-bold py-2 rounded-lg text-sm transition-colors">Enviar</button>
                    </form>
                </div>
            </div>

            <!-- Bottom -->
            <div
                class="border-t border-slate-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Ferretería Velazquez. Todos los derechos reservados.</p>
                <div class="flex gap-6">
                    <a href="{{ route('pages.privacy') }}" class="hover:text-white">Privacidad</a>
                    <a href="{{ route('pages.terms') }}" class="hover:text-white">Términos</a>
                    <a href="{{ route('pages.cookies') }}" class="hover:text-white">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Interactive Chatbot -->
    <livewire:store.chatbot />

    <!-- Toast Notifications -->
    <div x-data="{ 
            notifications: [],
            add(message, type = 'success') {
                this.notifications.push({
                    id: Date.now(),
                    message: message,
                    type: type,
                    show: true
                });
            },
            remove(id) {
                const index = this.notifications.findIndex(n => n.id === id);
                if (index > -1) {
                    this.notifications[index].show = false;
                    setTimeout(() => {
                        this.notifications = this.notifications.filter(n => n.id !== id);
                    }, 300);
                }
            }
        }" @notify.window="add($event.detail.message, $event.detail.type)"
        class="fixed bottom-4 right-4 z-50 space-y-2 pointer-events-none">

        <template x-for="notification in notifications" :key="notification.id">
            <div x-show="notification.show" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="pointer-events-auto max-w-sm w-full bg-white shadow-lg rounded-xl border-l-4 p-4 flex items-start gap-3"
                :class="{
                    'border-green-500': notification.type === 'success',
                    'border-red-500': notification.type === 'error',
                    'border-blue-500': notification.type === 'info'
                 }">

                <!-- Icons -->
                <div x-show="notification.type === 'success'" class="text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div x-show="notification.type === 'error'" class="text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-900" x-text="notification.message"></p>
                </div>

                <button @click="remove(notification.id)" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>

                <!-- Auto dismiss -->
                <div x-init="setTimeout(() => remove(notification.id), 3000)"></div>
            </div>
        </template>
    </div>
    <!-- Auth Modal -->
    <div x-show="authModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog"
        aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-dark/60 backdrop-blur-sm transition-opacity" x-show="authModal"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="authModal = false"></div>

        <!-- Modal Panel -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div x-show="authModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl overflow-hidden transform transition-all">

                <!-- Close Button -->
                <button @click="authModal = false"
                    class="absolute top-4 right-4 text-gray-400 hover:text-dark z-10 p-1 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Tabs -->
                <div class="flex border-b border-gray-100 bg-gray-50/50">
                    <button @click="activeTab = 'login'"
                        :class="{ 'text-primary border-b-2 border-secondary bg-white': activeTab === 'login', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'login' }"
                        class="flex-1 py-4 text-sm font-extrabold uppercase tracking-wider transition-all duration-200">
                        Iniciar Sesión
                    </button>
                    <button @click="activeTab = 'register'"
                        :class="{ 'text-primary border-b-2 border-secondary bg-white': activeTab === 'register', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'register' }"
                        class="flex-1 py-4 text-sm font-extrabold uppercase tracking-wider transition-all duration-200">
                        Registrarse
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 sm:p-8 bg-white">
                    <!-- Login Form -->
                    <div x-show="activeTab === 'login'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 -translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0">
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Usuario
                                    o Email</label>
                                <input type="text" name="login" value="{{ old('login') }}" required autofocus
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="ej. JuanPerez">
                                @error('login') <span
                                    class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>

                            <div x-data="{ show: false }">
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Contraseña</label>
                                <div class="relative">
                                    <input :type="show ? 'text' : 'password'" name="password" required
                                        class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none pr-10 placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                        placeholder="••••••••">
                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-secondary transition-colors">
                                        <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <span
                                class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end mb-2">
                                @if (Route::has('password.request'))
                                    <a class="text-xs font-bold text-gray-400 hover:text-secondary hover:underline transition-all duration-300 transform hover:translate-x-1"
                                        href="{{ route('password.request') }}">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                @endif
                            </div>

                            <button
                                class="group w-full bg-dark text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-2xl hover:bg-black transition-all duration-300 transform hover:-translate-y-1 active:scale-95 border-b-4 border-gray-800 hover:border-secondary relative overflow-hidden">
                                <span
                                    class="relative z-10 group-hover:text-secondary transition-colors duration-300">Iniciar
                                    Sesión</span>
                                <div
                                    class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div x-show="activeTab === 'register'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-x-4"
                        x-transition:enter-end="opacity-100 translate-x-0" style="display: none;">
                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Nombre</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="Tu nombre completo">
                                @error('name') <span
                                    class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Usuario</label>
                                <input type="text" name="username" value="{{ old('username') }}" required
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="Usuario único">
                                @error('username') <span
                                class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="tucorreo@ejemplo.com">
                                @error('email') <span
                                    class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Contraseña</label>
                                <input type="password" name="password" required
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="Mínimo 8 caracteres">
                                @error('password') <span
                                class="text-accent text-xs mt-1 block font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label
                                    class="block font-bold text-xs text-gray-400 uppercase tracking-widest mb-1.5 pl-1">Confirmar
                                    Contraseña</label>
                                <input type="password" name="password_confirmation" required
                                    class="block w-full bg-surface border-transparent focus:border-secondary rounded-xl px-4 py-3 text-dark font-medium focus:ring-4 focus:ring-secondary/20 transition-all duration-300 outline-none placeholder-gray-300 shadow-inner focus:shadow-xl transform focus:-translate-y-0.5"
                                    placeholder="Repite tu contraseña">
                            </div>

                            <button
                                class="group w-full bg-secondary hover:bg-yellow-400 text-dark font-bold py-4 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 active:scale-95 mt-2 border-b-4 border-yellow-500 hover:border-yellow-400 relative overflow-hidden">
                                <span class="relative z-10 group-hover:text-black transition-colors duration-300">Crear
                                    Cuenta</span>
                                <div
                                    class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>