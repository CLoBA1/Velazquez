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

            <!-- Top Actions -->
            <div class="flex items-center gap-3">
                <!-- Limpiar Ofertas Button -->
                <form action="{{ route('admin.offers.destroyAll') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar todas las ofertas activas? Esta acción restablecerá el precio normal para todos los productos en descuento.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-50 hover:bg-red-100 text-red-600 px-5 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2 border border-red-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Limpiar Ofertas
                    </button>
                </form>

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