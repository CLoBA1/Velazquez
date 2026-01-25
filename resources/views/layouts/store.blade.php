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
    @livewireStyles
</head>

<body
    class="bg-gray-50 text-slate-900 antialiased flex flex-col min-h-screen overflow-x-hidden selection:bg-orange-100 selection:text-orange-900">

    <!-- Navbar -->
    <nav x-data="{ open: false, searchOpen: false, scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="sticky top-0 z-40 transition-all duration-300 w-full"
        :class="{ 'bg-white/95 backdrop-blur shadow-sm border-b border-gray-100': scrolled, 'bg-white': !scrolled }">

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

                    <a href="{{ route('store.index') }}" class="flex items-center gap-2 group">
                        <div
                            class="bg-slate-900 text-white p-2 rounded-xl group-hover:scale-110 transition-transform duration-300 shadow-md">
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-lg tracking-tight text-slate-900 leading-tight">Ferretería
                                Velázquez</span>
                            <span class="text-[9px] uppercase tracking-wide text-slate-500 font-medium">Materiales para
                                Construcción</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('store.index') }}"
                        class="text-sm font-semibold {{ request()->routeIs('store.index') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }} transition-colors">Catálogo</a>
                    <a href="{{ route('store.brands.index') }}"
                        class="text-sm font-semibold {{ request()->routeIs('store.brands.*') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }} transition-colors">Marcas</a>
                    <a href="{{ route('store.offers.index') }}"
                        class="text-sm font-semibold {{ request()->routeIs('store.offers.*') ? 'text-orange-600' : 'text-slate-600 hover:text-orange-600' }} transition-colors">Ofertas</a>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-3">
                    <button @click="searchOpen = !searchOpen"
                        class="p-2.5 text-slate-600 hover:text-orange-600 bg-gray-50 hover:bg-orange-50 rounded-full transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>

                    @auth
                        <div x-data="{ openProfile: false }" class="relative">
                            <button @click="openProfile = !openProfile"
                                class="hidden sm:flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-orange-600 transition-colors px-4 py-2 bg-gray-50 hover:bg-orange-50 rounded-full border border-gray-100 hover:border-orange-100">
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="openProfile" @click.away="openProfile = false" style="display: none;"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 py-1 z-50">

                                @if(auth()->user()->isAdmin() || auth()->user()->isStaff())
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                        Admin Dashboard
                                    </a>
                                @endif

                                <!-- Aquí iría 'Mis Pedidos', etc para clientes -->
                                <a href="#"
                                    class="block px-4 py-2 text-sm text-slate-700 hover:bg-orange-50 hover:text-orange-600">Mis
                                    Compras</a>

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
                        <a href="{{ route('login') }}"
                            class="hidden sm:flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-orange-600 transition-colors px-4 py-2 bg-gray-50 hover:bg-orange-50 rounded-full border border-gray-100 hover:border-orange-100">
                            <span>Iniciar Sesión</span>
                        </a>
                        <a href="{{ route('register') }}"
                            class="hidden sm:flex items-center gap-2 text-sm font-bold text-white bg-orange-600 hover:bg-orange-700 transition-colors px-4 py-2 rounded-full shadow-lg shadow-orange-600/20">
                            <span>Registro</span>
                        </a>
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
                <a href="{{ route('store.index') }}"
                    class="block text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    Catálogo
                </a>
                <a href="{{ route('store.brands.index') }}"
                    class="block text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    Marcas
                </a>
                <a href="{{ route('store.offers.index') }}"
                    class="block text-base font-medium text-slate-700 hover:text-orange-600 transition-colors">
                    Ofertas
                </a>
            </div>
        </div>

        <!-- Search Overlay -->
        <div x-show="searchOpen" @click.away="searchOpen = false" style="display: none;"
            class="absolute top-full left-0 w-full bg-white border-y border-gray-100 shadow-xl py-6 px-4 z-40">
            <form action="{{ route('store.index') }}" method="GET" class="max-w-4xl mx-auto relative group">
                <input type="text" name="search" placeholder="Buscar productos..."
                    class="w-full pl-6 pr-14 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-lg focus:ring-2 focus:ring-orange-500 transition-all outline-none">
                <button type="submit"
                    class="absolute right-3 top-3 p-2 bg-orange-500 text-white rounded-xl hover:bg-orange-600"><svg
                        class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg></button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    <!-- Corrected Footer -->
    <footer class="bg-black text-white pt-16 pb-8 border-t border-slate-900 mt-auto w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Grid Layout Fix -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="bg-white/10 p-2 rounded-lg"><svg class="w-6 h-6 text-orange-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg></div>
                        <div class="flex flex-col">
                            <span class="font-bold text-lg leading-none">Ferretería Velázquez</span>
                            <span class="text-[10px] uppercase text-slate-400">Materiales para Construcción</span>
                        </div>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed">Calidad y confianza en cada herramienta.</p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Tienda</h3>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Catálogo Completo</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Ofertas del Mes</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Nuevos Productos</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Ayuda</h3>
                    <ul class="space-y-3 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Envíos</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Devoluciones</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Contacto</a></li>
                    </ul>
                </div>

                <!-- Newsletter -->
                <div>
                    <h3 class="font-bold text-sm uppercase tracking-wider mb-4 opacity-80">Suscríbete</h3>
                    <form class="flex flex-col gap-2">
                        <input type="email" placeholder="Email"
                            class="bg-slate-900 border border-slate-800 rounded-lg px-3 py-2 text-sm text-white w-full focus:ring-1 focus:ring-orange-500">
                        <button
                            class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 rounded-lg text-sm transition-colors">Enviar</button>
                    </form>
                </div>
            </div>

            <!-- Bottom -->
            <div
                class="border-t border-slate-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} Ferretería Velázquez. Todos los derechos reservados.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white">Privacidad</a>
                    <a href="#" class="hover:text-white">Términos</a>
                    <a href="#" class="hover:text-white">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
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
    @livewireScripts
</body>

</html>