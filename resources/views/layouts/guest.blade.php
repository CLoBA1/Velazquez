<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased bg-slate-900 selection:bg-orange-500 selection:text-white">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">

        <!-- Background Enhancements -->
        <div class="absolute inset-0 z-0 opacity-20"
            style="background-image: radial-gradient(#94a3b8 1px, transparent 1px); background-size: 32px 32px;"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-orange-600/20 rounded-full blur-3xl animate-pulse"></div>
        <div
            class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl animate-pulse delay-1000">
        </div>

        <div
            class="relative z-10 w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-2xl overflow-hidden sm:rounded-[2rem] border border-white/10">

            <!-- Logo -->
            <div class="flex flex-col items-center justify-center mb-8">
                <a href="/" class="flex items-center gap-3 group">
                    <div
                        class="bg-slate-900 text-white p-3 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="font-black text-2xl text-slate-800 tracking-tight leading-none group-hover:text-orange-600 transition-colors">Ferretería<br>Velázquez</span>
                    </div>
                </a>
            </div>

            <!-- Slot -->
            <div>
                {{ $slot }}
            </div>
        </div>

        <div class="relative z-10 mt-8 text-slate-500 text-xs text-center">
            &copy; {{ date('Y') }} Ferretería Velázquez. <br>Calidad y confianza en cada herramienta.
        </div>
    </div>
</body>

</html>