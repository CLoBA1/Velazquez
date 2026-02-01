@extends('layouts.store')

@section('title', 'Bienvenido a Ferretería Velázquez | Herramientas y Materiales')

@section('content')
    <div
        class="relative min-h-[85vh] flex flex-col items-center justify-center overflow-hidden bg-white selection:bg-secondary/20 selection:text-primary">
        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 right-0 w-1/2 h-full bg-slate-50/50 skew-x-12 transform origin-top-right"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-secondary/10 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Main Logo/Header -->
            <div class="mb-12 animate-fade-in-up">
                <!-- Title hidden as per user request to avoid duplication with Header
                    <h1
                        class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight mb-6 leading-none animate-fade-in-up">
                        Ferretería <span class="text-primary">Velazquez</span>
                    </h1>
                    -->
                <p class="text-xl md:text-2xl text-slate-500 font-light max-w-2xl mx-auto">
                    Tu socio experto en construcción, herramientas y maquinaria.
                </p>
            </div>

            <!-- Ecosystem Selectors -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto mt-16 text-left">

                <!-- Ferretería -->
                <a href="{{ route('store.index') }}"
                    class="group relative bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-gray-500/10 hover:-translate-y-2 transition-all duration-300 flex flex-col animate-fade-in-up delay-100 overflow-hidden">
                    <div
                        class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-32 h-32 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <div
                        class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 text-primary group-hover:bg-primary group-hover:text-secondary transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-primary transition-colors">
                        Ferretería</h2>
                    <p class="text-slate-500 mb-8 flex-grow">Herramientas manuales, eléctricas, plomería y eléctricos para
                        tu hogar o taller.</p>

                    <div class="flex items-center text-primary font-semibold group-hover:gap-2 transition-all">
                        Explorar Catálogo <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                <!-- Materiales -->
                <a href="{{ route('construction.index') }}"
                    class="group relative bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2 transition-all duration-300 flex flex-col animate-fade-in-up delay-200 overflow-hidden">
                    <div
                        class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-32 h-32 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>

                    <div
                        class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-6 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors">
                        Construcción</h2>
                    <p class="text-slate-500 mb-8 flex-grow">Materiales pesados, cemento, varilla, agregados y todo para tu
                        obra negra.</p>

                    <div class="flex items-center text-blue-600 font-semibold group-hover:gap-2 transition-all">
                        Ver Materiales <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

                <!-- Maquinaria -->
                <a href="{{ route('machinery.index') }}"
                    class="group relative bg-white rounded-3xl p-8 border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-yellow-500/10 hover:-translate-y-2 transition-all duration-300 flex flex-col animate-fade-in-up delay-300 overflow-hidden">
                    <div
                        class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity transform group-hover:scale-110 duration-500">
                        <svg class="w-32 h-32 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>

                    <div
                        class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center mb-6 text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-900 mb-2 group-hover:text-yellow-600 transition-colors">
                        Maquinaria</h2>
                    <p class="text-slate-500 mb-8 flex-grow">Renta de maquinaria ligera y pesada. Revolvedoras, bailarinas y
                        más.</p>

                    <div class="flex items-center text-yellow-600 font-semibold group-hover:gap-2 transition-all">
                        Ver Equipos <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>

            </div>

            <div class="mt-20 animate-fade-in delay-300">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-slate-50 text-slate-500 text-sm font-medium">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Tienda Abierta: Lunes a Sábado 8:00 AM - 6:00 PM
                </div>
            </div>
        </div>
    </div>
@endsection