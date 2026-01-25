<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Denegado - Ferretería Velázquez</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-950 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-8">
        <!-- Icon -->
        <div class="mx-auto w-24 h-24 bg-orange-500/10 rounded-full flex items-center justify-center mb-6">
            <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                </path>
            </svg>
        </div>

        <!-- Text -->
        <div class="space-y-4">
            <h1 class="text-4xl font-bold tracking-tight">Acceso Restringido</h1>
            <p class="text-slate-400 text-lg">
                Lo sentimos, no tienes los permisos necesarios para ver esta sección.
            </p>
        </div>

        <!-- Action -->
        <div class="pt-6">
            <a href="{{ url()->previous() == url()->current() ? route('admin.dashboard') : url()->previous() }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg shadow-orange-900/20 transform hover:-translate-y-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Regresar
            </a>
        </div>

        <div class="pt-12 border-t border-slate-800 mt-12">
            <p class="text-slate-600 text-xs uppercase tracking-widest">Ferretería Velázquez</p>
        </div>
    </div>
</body>

</html>