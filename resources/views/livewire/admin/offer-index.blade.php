<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Feedback Message -->
    @if (session()->has('ok'))
        <div class="bg-green-50 text-green-700 p-4 font-bold text-center">
            {{ session('ok') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead
                class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Producto</th>
                    <th class="px-6 py-4 text-center">Precio Normal</th>
                    <th class="px-6 py-4 text-center">Precio Oferta</th>
                    <th class="px-6 py-4 text-center">Descuento</th>
                    <th class="px-6 py-4 text-center">Termina</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($offers as $offer)
                    <tr wire:key="offer-{{ $offer->id }}" class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 p-1 border border-gray-200 flex-shrink-0">
                                    @if($offer->image_url)
                                        <img src="{{ $offer->image_url }}"
                                            class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-bold text-dark">{{ $offer->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $offer->internal_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center font-medium text-slate-500">
                            ${{ number_format($offer->public_price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-primary bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                                ${{ number_format($offer->sale_price, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $discountPercent = $offer->public_price > 0 ? (1 - $offer->sale_price / $offer->public_price) * 100 : 0;
                            @endphp

                            @if($discountPercent <= 0)
                                <div class="inline-flex flex-col items-center">
                                    <span class="font-bold text-slate-400 text-xs bg-slate-100 px-2 py-0.5 rounded">Sin
                                        Descuento</span>
                                    <span class="text-[10px] text-red-400 mt-1">No visible en tienda</span>
                                </div>
                            @else
                                <div class="inline-flex flex-col items-center">
                                    <span class="font-bold text-green-600 text-xs">
                                        -{{ number_format($discountPercent, 0) }}%
                                    </span>
                                    <span class="text-[10px] text-slate-400">Ahorras
                                        ${{ number_format($offer->public_price - $offer->sale_price, 2) }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs">
                            @if($offer->sale_deadline)
                                @if(\Carbon\Carbon::parse($offer->sale_deadline)->isPast())
                                    <span class="text-red-500 font-bold">Expirada</span>
                                @else
                                    <div class="font-bold text-slate-700">
                                        {{ \Carbon\Carbon::parse($offer->sale_deadline)->format('d M Y') }}</div>
                                    <div class="text-slate-400">{{ \Carbon\Carbon::parse($offer->sale_deadline)->format('h:i A') }}
                                    </div>
                                @endif
                            @else
                                <span class="text-slate-400 italic">Sin fecha límite</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Edit: Dispatch to Livewire Modal -->
                                <button type="button" wire:click="$dispatch('edit-offer', { productId: {{ $offer->id }} })"
                                    class="p-2 text-slate-400 hover:text-primary hover:bg-blue-50 rounded-lg transition-colors"
                                    title="Editar Oferta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>

                                <!-- Delete: Direct Livewire Action with Confirm -->
                                <button type="button" wire:click="delete({{ $offer->id }})"
                                    wire:confirm="¿Estás seguro de quitar esta oferta?"
                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Eliminar Oferta">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-slate-900 font-bold text-lg">No hay ofertas activas</h3>
                                <p class="text-slate-500 mt-1 max-w-sm">Agrega ofertas usando el botón "Nueva Oferta".</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($offers->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
            {{ $offers->links() }}
        </div>
    @endif
</div>