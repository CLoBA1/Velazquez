@extends('admin.layouts.app')

@section('title', 'Categorías')

@section('content')
    <!-- Hero Header (Dark Premium) -->
    <div class="relative bg-slate-900 rounded-3xl p-8 mb-8 overflow-hidden shadow-2xl">
        <!-- Background Decor -->
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-slate-800 rounded-full blur-3xl opacity-50"></div>
        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 bg-orange-900 rounded-full blur-3xl opacity-30"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    <span
                        class="bg-gradient-to-br from-orange-400 to-orange-600 w-3 h-8 rounded-full shadow-lg shadow-orange-500/50"></span>
                    Categorías
                </h1>
                <p class="text-slate-400 mt-2 text-lg font-medium pl-6">Organiza tus productos por departamentos.</p>
            </div>

            <div class="flex items-center gap-3 pl-6 md:pl-0">
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600 shadow-xl shadow-orange-900/40 transition-all hover:-translate-y-1 hover:shadow-orange-900/60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Categoría
                </a>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-xl shadow-slate-200/50 mb-8">
        <form method="GET" action="{{ route('admin.categories.index') }}"
            class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">

            <div class="md:col-span-10 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar</label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre..."
                            class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400 group-focus-within:text-orange-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Familia</label>
                    <div class="relative group">
                        <select name="family_id" onchange="this.form.submit()"
                            class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-3 text-sm focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium appearance-none">
                            <option value="">Todas</option>
                            @foreach($families as $f)
                                <option value="{{ $f->id }}" {{ request('family_id') == $f->id ? 'selected' : '' }}>
                                    {{ $f->name }}
                                </option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 group-focus-within:text-orange-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 flex gap-2">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-0.5">
                    Filtrar
                </button>
                @if(request('search') || request('family_id'))
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center justify-center p-3 rounded-xl border border-dashed border-red-200 bg-red-50 text-red-500 hover:bg-red-100 hover:border-red-300 transition-all"
                        title="Limpiar filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </a>
                @endif
            </div>

        </form>
    </div>

    @if(session('ok'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-white p-5 text-emerald-800 flex items-center gap-4 shadow-lg shadow-emerald-50/50"
            role="alert">
            <div class="bg-emerald-100 p-2 rounded-full">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="font-bold text-lg">{{ session('ok') }}</span>
        </div>
    @endif

    <div class="overflow-hidden rounded-[2rem] border border-slate-100 bg-white shadow-xl shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Familia
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Nombre
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Slug</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($categories as $c)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 font-mono font-medium">{{ $c->id }}</td>
                            <td class="px-6 py-4">
                                @if($c->family)
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700 ring-1 ring-indigo-100">
                                        {{ $c->family->name }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-xs italic">Sin familia</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-700 text-base">{{ $c->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 font-mono text-xs">
                                {{ $c->slug }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.categories.edit', $c) }}"
                                        class="p-2 rounded-xl text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all"
                                        title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('¿Eliminar categoría?')"
                                            class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition-all"
                                            title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-12 text-center text-slate-400" colspan="5">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-slate-50 p-4 rounded-full mb-3">
                                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="font-medium text-slate-600">No hay categorías registradas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $categories->links() }}
    </div>
@endsection