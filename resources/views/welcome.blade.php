@extends('layouts.store')

@section('title', 'Bienvenido a Ferretería Velázquez | Herramientas y Materiales')
@section('hide_main_header_brand', true)

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
                <h1
                    class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight mb-6 leading-none animate-fade-in-up">
                    Ferretería <span class="text-primary">Velazquez</span>
                </h1>
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
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.7-3.7a1 1 0 0 0 0-1.4 1 1 0 0 0-1.4 0L14.7 6.3zm-3.2 1.6a1 1 0 0 0-1.4 0L2.3 15.7a1 1 0 0 0 0 1.4l2.6 2.6a1 1 0 0 0 1.4 0l7.8-7.8a1 1 0 0 0 0-1.4l-2.6-2.6z" />
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
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <div
                        class="w-16 h-16 bg-yellow-50 rounded-2xl flex items-center justify-center mb-6 text-yellow-600 group-hover:bg-yellow-500 group-hover:text-white transition-colors duration-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.8 17.6c.4.5.2 1.3-.4 1.5-.2.1-.5.1-.7.1-1.3 0-2.5-.5-3.4-1.4l-3-3C11.5 14.1 10.3 13.9 9 14.1l-.2.1c-1.3.1-2.6.4-3.7 1-.7.4-1.6.4-2.2-.2L2 14c-.3-.3-.4-.8-.1-1.2l2.3-3.6c.3-.5.9-.7 1.5-.7h3.8c.3 0 .6.1.9.2l1.3.6c.7.3 1.5.3 2.2 0l4.3-2c.6-.3 1.3-.2 1.8.3l1.5 1.5c.5.5.5 1.3 0 1.8l-1.9 1.9c-.3.3-.3.8 0 1.1l1.5 1.5c.3.3.3.8 0 1.2z" />
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