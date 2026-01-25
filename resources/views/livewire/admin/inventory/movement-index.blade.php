<div class="flex flex-col gap-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Movimientos de Inventario</h1>
            <p class="text-slate-500 mt-1">Historial completo de entradas, salidas y ajustes de stock.</p>
        </div>

        <div class="flex items-center gap-3">
            <button wire:click="downloadPdf" wire:loading.attr="disabled"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
                <svg wire:loading.remove wire:target="downloadPdf" class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <svg wire:loading wire:target="downloadPdf" class="animate-spin -ml-1 mr-3 h-5 w-5 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="downloadPdf">Reporte PDF</span>
                <span wire:loading wire:target="downloadPdf">Generando...</span>
            </button>
            <a href="{{ route('admin.inventory.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 shadow-lg shadow-slate-900/20 transition-all hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Ajuste
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar Producto</label>
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre o SKU..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tipo</label>
                <select wire:model.live="type"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
                    <option value="">Todos</option>
                    <option value="purchase">Compra</option>
                    <option value="sale">Venta</option>
                    <option value="adjustment_add">Ajuste (+)</option>
                    <option value="adjustment_sub">Ajuste (-)</option>
                    <option value="return">Devolución</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Desde</label>
                <input type="date" wire:model.live="date_from"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hasta</label>
                <input type="date" wire:model.live="date_to"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Producto</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Tipo</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Cantidad</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Stock Anterior</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">Stock Nuevo</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">Notas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($movements as $movement)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 font-mono">
                                {{ $movement->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $movement->product->name }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $movement->product->internal_code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeColors = [
                                        'purchase' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                        'adjustment_add' => 'bg-blue-50 text-blue-700 border-blue-100',
                                        'return' => 'bg-purple-50 text-purple-700 border-purple-100',
                                        'sale' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'adjustment_sub' => 'bg-amber-50 text-amber-700 border-amber-100',
                                    ];
                                    $colorClass = $typeColors[$movement->type] ?? 'bg-gray-50 text-gray-700';
                                    
                                    $typeLabels = [
                                        'purchase' => 'Compra',
                                        'adjustment_add' => 'Ajuste (+)',
                                        'return' => 'Devolución',
                                        'sale' => 'Venta',
                                        'adjustment_sub' => 'Ajuste (-)',
                                    ];
                                @endphp
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-bold {{ $colorClass }}">
                                    {{ $typeLabels[$movement->type] ?? ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-sm">
                                @if(in_array($movement->type, ['purchase', 'adjustment_add', 'return']))
                                    <span class="text-emerald-600">+{{ $movement->quantity }}</span>
                                @else
                                    <span class="text-red-600">-{{ $movement->quantity }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-slate-500 font-mono">
                                {{ $movement->previous_stock }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-slate-700 font-mono">
                                {{ $movement->new_stock }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $movement->user->name ?? 'Sistema' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-400 italic">
                                {{ Str::limit($movement->notes, 30) ?: '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    <p class="text-base font-medium text-slate-600">No se encontraron movimientos.</p>
                                    <p class="text-sm text-slate-400 mt-1">Intenta ajustar los filtros de búsqueda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $movements->links() }}
    </div>
</div>