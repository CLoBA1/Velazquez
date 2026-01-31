<div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Cotizar Renta</h3>

    <form action="{{ route('rentals.store') }}" method="POST">
        @csrf
        <input type="hidden" name="machine_id" value="{{ $machine->id }}">

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                <input type="date" wire:model.live="startDate" name="start_date" min="{{ date('Y-m-d') }}" required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                <input type="date" wire:model.live="endDate" name="end_date" min="{{ $startDate ?? date('Y-m-d') }}"
                    required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            @if($days > 0)
                <div class="bg-white p-4 rounded-lg border border-gray-200 mt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-500">{{ $days }} días x
                            ${{ number_format($machine->price_per_day, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                        <span class="font-bold text-gray-900">Total Estimado</span>
                        <span class="font-black text-xl text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @auth
                    <button type="submit"
                        class="w-full mt-4 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Generar Orden de Renta
                    </button>
                    <p class="text-xs text-gray-500 text-center mt-2">La orden quedará pendiente de aprobación.</p>
                @else
                    <button type="button" @click="authModal = true; activeTab = 'login'"
                        class="w-full mt-4 bg-gray-800 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-gray-900 transition-colors">
                        Inicia Sesión para Rentar
                    </button>
                @endauth
            @else
                <div
                    class="text-sm text-gray-500 text-center py-4 bg-gray-100/50 rounded-lg border border-dashed border-gray-200">
                    Selecciona fechas para ver el presupuesto
                </div>
            @endif
        </div>
    </form>
</div>