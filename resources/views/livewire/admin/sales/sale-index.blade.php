<div>
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Header & Filters -->
        <div
            class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <h2 class="font-bold text-slate-700 text-lg">Historial de Ventas</h2>

            <div class="flex gap-2 w-full sm:w-auto">
                <!-- Search -->
                <div class="relative flex-1 sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Buscar por Folio o Cliente..."
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-300 focus:ring-orange-500 focus:border-orange-500 text-sm">
                    <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <!-- Filter -->
                <select wire:model.live="sourceFilter"
                    class="rounded-lg border border-slate-300 focus:ring-orange-500 focus:border-orange-500 text-sm py-2">
                    <option value="">Todas</option>
                    <option value="web">Tienda Web</option>
                    <option value="pos">Punto de Venta</option>
                </select>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead
                    class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Folio</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Cliente / Usuario</th>
                        <th class="px-6 py-4 text-center">Origen</th>
                        <th class="px-6 py-4 text-center">Método</th>
                        <th class="px-6 py-4 text-right">Total</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sales as $sale)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 font-mono font-bold text-indigo-600">
                                            #{{ $sale->id }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $sale->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <div class="flex flex-col">
                                                    @if($sale->source === 'web')
                                                        {{-- Web Sale: User is the Client --}}
                                                        @if($sale->user)
                                                            <span class="font-bold text-slate-700">{{ $sale->user->name }}</span>
                                                            <span class="text-[10px] text-blue-500 font-medium">Cliente Web</span>
                                                        @else
                                                            <span class="text-slate-400 italic">Invitado / Público</span>
                                                        @endif
                                                    @else
                                                        {{-- POS Sale --}}
                                                        @if($sale->client)
                                                            <span class="font-bold text-slate-700">{{ $sale->client->name }}</span>
                                                        @else
                                                            <span class="text-slate-400 italic">Público General</span>
                                                        @endif

                                                        @if($sale->user)
                                                            <span class="text-xs text-slate-400 mt-0.5">Atendió: {{ $sale->user->name }}</span>
                                                        @endif
                                                    @endif

                                                    @if($sale->source === 'web' && $sale->shipping_address)
                                                        <span class="text-[10px] text-orange-600 mt-1 truncate max-w-[200px]"
                                                            title="{{ $sale->shipping_address }}">
                                                            📍 Envío
                                                        </span>
                                                    @endif
                                                </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($sale->source === 'web')
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                    🌐 Web
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    🖥️ POS
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center capitalize">
                                            {{ $sale->payment_method }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-bold text-slate-900">
                                            ${{ number_format($sale->total, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="capitalize px-2 py-1 rounded text-xs font-bold
                                                                    {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700' :
                        ($sale->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700') }}">
                                                {{ $sale->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.sales.pdf', $sale) }}" target="_blank"
                                                class="text-indigo-600 hover:text-indigo-800 font-medium text-xs hover:underline">
                                                Ver Ticket
                                            </a>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                No se encontraron ventas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($sales->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>