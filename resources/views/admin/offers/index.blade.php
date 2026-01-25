@extends('admin.layouts.app')

@section('title', 'Gestión de Ofertas')

@section('content')
    <div class="flex flex-col gap-8">

        <!-- Active Offers Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Gestión de Ofertas</h1>
                <p class="text-slate-500 mt-1">Administra los descuentos y promociones activas.</p>
            </div>

            <!-- Livewire Search to Add Offer -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nueva Oferta
                </button>

                <!-- Dropdown/Modal for searching products -->
                <div x-show="open" @click.away="open = false" style="display: none;"
                    class="absolute right-0 mt-4 w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 z-50 origin-top-right transition-all">
                    <livewire:admin.product-search-for-offer />
                </div>
            </div>
        </div>

        <!-- Offers List (Livewire) -->
        <livewire:admin.offer-index />

        <!-- We need a modal for editing/creating offers easily. I'll rely on Livewire for this to keep it dynamic. -->
        <livewire:admin.offer-manager-modal />

    </div>
@endsection