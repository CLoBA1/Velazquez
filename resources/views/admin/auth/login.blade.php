<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="flex items-center justify-center mb-6">
                <div class="h-12 w-12 rounded-xl bg-slate-900 text-white grid place-items-center font-bold">
                    F
                </div>
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-6">
                <div class="mb-6">
                    <h1 class="text-2xl font-bold">Iniciar sesión</h1>
                    <p class="text-sm text-slate-600 mt-1">Acceso al panel de administración</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <p class="font-semibold mb-1">Revisa lo siguiente:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-800">Usuario</label>
                        <input type="text"
                               name="username"
                               value="{{ old('username') }}"
                               required
                               autofocus
                               autocomplete="username"
                               class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-300">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-800">Contraseña</label>
                        <input type="password"
                               name="password"
                               required
                               autocomplete="current-password"
                               class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-300">
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900/20">
                        Entrar
                    </button>

                    <p class="text-xs text-slate-500 text-center">
                        Ferretería Velázquez · Administración
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>