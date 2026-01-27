<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-black text-dark tracking-tight">Crear Cuenta</h2>
        <p class="text-gray-500 text-sm">Únete a nosotros para comprar fácil y rápido</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block font-bold text-xs text-gray-500 uppercase tracking-widest mb-1 pl-1">Nombre
                Completo</label>
            <input id="name"
                class="block w-full bg-light border border-gray-200 rounded-xl px-4 py-3 text-dark font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                placeholder="Tu nombre completo" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div>
            <label for="username"
                class="block font-bold text-xs text-gray-500 uppercase tracking-widest mb-1 pl-1">Usuario</label>
            <input id="username"
                class="block w-full bg-light border border-gray-200 rounded-xl px-4 py-3 text-dark font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                type="text" name="username" :value="old('username')" required autocomplete="username"
                placeholder="Nombre de usuario único" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email"
                class="block font-bold text-xs text-gray-500 uppercase tracking-widest mb-1 pl-1">Email</label>
            <input id="email"
                class="block w-full bg-light border border-gray-200 rounded-xl px-4 py-3 text-dark font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                type="email" name="email" :value="old('email')" required autocomplete="username"
                placeholder="tucorreo@ejemplo.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password"
                class="block font-bold text-xs text-gray-500 uppercase tracking-widest mb-1 pl-1">Contraseña</label>
            <input id="password"
                class="block w-full bg-light border border-gray-200 rounded-xl px-4 py-3 text-dark font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                type="password" name="password" required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation"
                class="block font-bold text-xs text-gray-500 uppercase tracking-widest mb-1 pl-1">Confirmar
                Contraseña</label>
            <input id="password_confirmation"
                class="block w-full bg-light border border-gray-200 rounded-xl px-4 py-3 text-dark font-medium focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none"
                type="password" name="password_confirmation" required autocomplete="new-password"
                placeholder="Repite tu contraseña" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
            class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-xl shadow-blue-500/20 hover:shadow-blue-600/30 transition-all transform hover:-translate-y-1 duration-300 mt-2">
            Registrarse
        </button>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="font-bold text-primary hover:text-blue-700 hover:underline">Inicia
                    Sesión</a>
            </p>
        </div>
    </form>
</x-guest-layout>