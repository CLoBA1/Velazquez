@extends('admin.layouts.app')

@section('title', 'Gestión de Usuarios')
@section('page_title', 'Usuarios')

@section('content')
    <!-- Hero Header -->
    <div class="relative bg-slate-900 rounded-3xl p-8 mb-8 overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-slate-800 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-indigo-900 rounded-full blur-3xl opacity-30"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span class="bg-gradient-to-br from-indigo-400 to-indigo-600 w-3 h-8 rounded-full shadow-lg shadow-indigo-500/50"></span>
                    Usuarios
                </h1>
                <p class="text-slate-400 mt-2 text-lg font-medium pl-6">Administra roles y accesos del personal y clientes.</p>
            </div>

            <div class="flex items-center gap-3 pl-6 md:pl-0">
                <a href="{{ route('admin.users.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-500 px-5 py-3 text-sm font-bold text-white hover:bg-indigo-600 shadow-xl shadow-indigo-900/40 transition-all hover:-translate-y-1 hover:shadow-indigo-900/60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Nuevo Usuario
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-white p-5 text-emerald-800 flex items-center gap-4 shadow-lg shadow-emerald-50/50" role="alert">
            <div class="bg-emerald-100 p-2 rounded-full">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="font-bold text-lg">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Filters -->
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 mb-10">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Filtrar por Rol</label>
                <select name="role" class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-3 text-sm text-slate-700 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition-all font-medium">
                    <option value="">Todos</option>
                    <option value="admin" @selected(request('role') == 'admin')>Admin</option>
                    <option value="staff" @selected(request('role') == 'staff')>Staff</option>
                    <option value="customer" @selected(request('role') == 'customer')>Cliente</option>
                </select>
            </div>
            <div>
                <button type="submit" class="rounded-xl bg-slate-900 py-3 px-6 text-sm font-bold text-white hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-0.5">
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nombre</th>
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Usuario</th>
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Rol</th>
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Registrado</th>
                        <th class="p-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="p-6 font-bold text-slate-900">{{ $user->name }}</td>
                            <td class="p-6 text-slate-600 font-medium">{{ $user->username ?? 'N/A' }}</td>
                            <td class="p-6 text-slate-600 font-medium">{{ $user->email }}</td>
                            <td class="p-6">
                                @if($user->role === 'admin')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-purple-100 text-purple-700 text-xs font-bold border border-purple-200 uppercase tracking-wide">Admin</span>
                                @elseif($user->role === 'staff')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200 uppercase tracking-wide">Staff</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200 uppercase tracking-wide">Cliente</span>
                                @endif
                            </td>
                            <td class="p-6 text-slate-500 text-sm font-medium">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="p-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-500 hover:text-white transition-all" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-slate-500">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>
@endsection
