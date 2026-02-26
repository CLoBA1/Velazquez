<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                <!-- Backdrop -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="close"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal Panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Ajuste Rápido de Stock
                                </h3>
                                @if($product)
                                    <div class="mt-2 text-sm text-gray-500">
                                        <p class="font-bold text-slate-800">{{ $product->name }}</p>
                                        <p>Stock Actual: <span class="font-bold text-orange-600">{{ $product->stock }}
                                                {{ $product->unit->symbol ?? 'Pz' }}</span></p>
                                    </div>

                                    <div class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Cantidad a
                                                Agregar/Restar (en {{ $product->unit->name ?? 'Unidad Base' }})</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <input type="number" step="any" wire:model="amount" placeholder="+10 o -5"
                                                    class="focus:ring-orange-500 focus:border-orange-500 block w-full pl-4 sm:text-lg border-gray-300 rounded-xl"
                                                    autofocus>
                                            </div>
                                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            <p class="text-xs text-orange-600 font-medium mt-1">Nota: Ingresa la cantidad
                                                calculada en tu Unidad Base. Usa números negativos para restar.</p>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                                            <input type="text" wire:model="notes"
                                                class="focus:ring-orange-500 focus:border-orange-500 block w-full sm:text-sm border-gray-300 rounded-xl">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="save" type="button"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-orange-600 text-base font-medium text-white hover:bg-orange-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Guardar Ajuste
                        </button>
                        <button wire:click="close" type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>