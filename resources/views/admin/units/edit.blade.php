@extends('admin.layouts.app')

@section('title', 'Editar unidad')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Editar unidad</h1>
            <p class="text-slate-500 mt-1">Actualiza la unidad de medida.</p>
        </div>

        <a href="{{ route('admin.units.index') }}"
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Volver
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-100 bg-red-50 p-4 shadow-sm">
            <div class="flex items-start gap-4">
                <svg class="w-6 h-6 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="space-y-1">
                    <p class="font-bold text-red-900">Por favor corrige los siguientes errores:</p>
                    <ul class="list-disc leading-5 pl-5 text-sm text-red-800 space-y-1 pt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-3xl mx-auto rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.units.update', $unit) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nombre de la Unidad</label>
                    <input name="name" value="{{ old('name', $unit->name) }}" required
                        class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Símbolo</label>
                    <input name="symbol" value="{{ old('symbol', $unit->symbol) }}" required
                        class="w-full rounded-xl border-slate-200 py-2.5 px-3 text-sm font-bold text-center w-32 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all">
                </div>

                <div class="flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <input id="allows_decimal" type="checkbox" name="allows_decimal" value="1"
                        @checked(old('allows_decimal', $unit->allows_decimal))
                        class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-blue-500">
                    <label for="allows_decimal" class="text-sm font-bold text-slate-700 select-none cursor-pointer">
                        Permite valores decimales
                        <span class="block text-xs font-normal text-slate-500 mt-0.5">Habilita esta opción si la unidad
                            puede fraccionarse (ej. 1.5 kg, 0.5 m).</span>
                    </label>
                </div>
            </div>

            <div class="pt-4 flex flex-col gap-3">
                <button type="submit"
                    class="w-full rounded-xl bg-slate-900 py-3.5 text-sm font-bold text-white shadow-xl shadow-slate-900/10 hover:bg-slate-800 transition-all hover:scale-[1.02]">
                    Actualizar Unidad
                </button>
                <a href="{{ route('admin.units.index') }}"
                    class="w-full rounded-xl border border-slate-200 bg-white py-3.5 text-center text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
@endsection