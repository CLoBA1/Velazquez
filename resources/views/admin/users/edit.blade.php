@extends('admin.layouts.app')

@section('title', 'Editar Usuario')
@section('page_title', 'Editar Usuario')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Editar Usuario</h1>
            <a href="{{ route('admin.users.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 underline">Volver al listado</a>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-8">
            <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre Completo</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Usuario</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('username') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Rol</label>
                    <select name="role" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                        <option value="customer" @selected($user->role === 'customer')>Cliente</option>
                        <option value="staff" @selected($user->role === 'staff')>Staff</option>
                        <option value="admin" @selected($user->role === 'admin')>Administrador</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-slate-100 pt-6 mt-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Cambiar Contraseña (Opcional)</h3>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nueva Contraseña</label>
                            <input type="password" name="password"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                            @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-50 flex justify-end">
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white hover:bg-indigo-700 hover:-translate-y-0.5 shadow-lg shadow-indigo-500/30 transition-all">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection