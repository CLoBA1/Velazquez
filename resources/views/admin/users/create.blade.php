@extends('admin.layouts.app')

@section('title', 'Nuevo Usuario')
@section('page_title', 'Crear Usuario')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Nuevo Usuario</h1>
            <a href="{{ route('admin.users.index') }}"
                class="text-sm font-bold text-slate-500 hover:text-slate-800 underline">Volver al listado</a>
        </div>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 p-8">
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre Completo</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('name') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Usuario</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('username') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('email') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Rol</label>
                    <select name="role" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                        <option value="customer">Cliente</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Administrador</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Contraseña</label>
                    <input type="password" name="password" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                    @error('password') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-slate-800 font-medium focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all">
                </div>

                <div class="pt-4 border-t border-slate-50 flex justify-end">
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white hover:bg-indigo-700 hover:-translate-y-0.5 shadow-lg shadow-indigo-500/30 transition-all">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection