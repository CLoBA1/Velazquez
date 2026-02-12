@extends('admin.layouts.app')

@section('title', 'Inventario Premium')
@section('page_title', 'Productos')

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
                    Inventario Maestro
                </h1>
                <p class="text-slate-400 mt-2 text-lg font-medium pl-6">Gestión de alto rendimiento para tu catálogo.</p>
            </div>

            <div class="flex items-center gap-3 pl-6 md:pl-0">
                <a href="{{ route('admin.products.import.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-white/10 backdrop-blur-md px-5 py-3 text-sm font-bold text-white hover:bg-white/20 transition-all border border-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Importar Excel
                </a>
                <a href="{{ route('admin.products.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-orange-500 px-5 py-3 text-sm font-bold text-white hover:bg-orange-600 shadow-xl shadow-orange-900/40 transition-all hover:-translate-y-1 hover:shadow-orange-900/60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Producto
                </a>
            </div>
        </div>
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

    {{-- Filters (White & Clean) --}}
    <div class="rounded-3xl border border-slate-100 bg-white p-8 shadow-xl shadow-slate-200/50 mb-10" x-data="{
                filterCategories() {
                    let fam = this.$refs.family.value;
                    let catSelect = this.$refs.category;
                    let options = Array.from(catSelect.options);

                    options.forEach(opt => {
                        if (opt.value === '') return;
                        let famId = opt.dataset.familyId;
                        if (!fam || famId === fam) {
                            opt.hidden = false;
                        } else {
                            opt.hidden = true;
                        }
                    });

                    // Reset category if the selected one is now hidden
                    let selectedOpt = options[catSelect.selectedIndex];
                    if (selectedOpt && selectedOpt.hidden) {
                        catSelect.value = '';
                    }
                }
             }" x-init="filterCategories()"
             @scan-completed.window="
                const input = document.getElementById('searchInput');
                if (input) {
                    input.value = $event.detail.code;
                    input.form.submit();
                }
             "
    >

        <form method="GET" action="{{ route('admin.products.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <div class="md:col-span-2" x-data>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar</label>
                <div class="flex gap-2">
                    <div class="relative group flex-1">
                        <input id="searchInput" name="search" value="{{ request('search') }}" placeholder="Nombre, código, SKU..."
                            class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-800 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                        <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400 group-focus-within:text-orange-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="button" @click="$dispatch('open-scanner')" class="bg-slate-900 text-white px-3 rounded-xl hover:bg-slate-700 transition-colors shadow-md" title="Escanear">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Familia</label>
                <select name="family_id" x-ref="family" @change="filterCategories()"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-3 text-sm text-slate-700 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                    <option value="">Todas</option>
                    @foreach($families as $f)
                        <option value="{{ $f->id }}" @selected((string) request('family_id') === (string) $f->id)>{{ $f->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Categoría</label>
                <select name="category_id" x-ref="category"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-3 text-sm text-slate-700 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                    <option value="">Todas</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" data-family-id="{{ $c->family_id }}"
                            @selected((string) request('category_id') === (string) $c->id)>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Marca</label>
                <select name="brand_id"
                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-3 text-sm text-slate-700 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                    <option value="">Todas</option>
                    @foreach($brands as $b)
                        <option value="{{ $b->id }}" @selected((string) request('brand_id') === (string) $b->id)>{{ $b->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button
                    class="flex-1 rounded-xl bg-slate-900 py-3 text-sm font-bold text-white hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20 hover:-translate-y-0.5"
                    type="submit">
                    Filtrar
                </button>
                @if(request()->anyFilled(['search', 'family_id', 'category_id', 'brand_id']))
                    <a class="flex items-center justify-center w-11 h-11 rounded-xl border border-dashed border-red-200 bg-red-50 text-red-500 hover:bg-red-100 hover:border-red-300 transition-all"
                        href="{{ route('admin.products.index') }}" title="Limpiar filtros">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Product Grid (Hybrid: White Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($products as $p)
            <div
                class="group relative bg-white rounded-3xl p-[1px] shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-orange-500/20 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                
                <!-- Gradient Border via Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-slate-100 via-white to-orange-50 group-hover:from-orange-400 group-hover:to-orange-600 transition-colors duration-500 rounded-3xl"></div>

                <!-- Card Content -->
                <div class="relative h-full bg-white rounded-[23px] overflow-hidden flex flex-col">
                    
                    <!-- Image Area -->
                    <div class="relative aspect-[4/3] bg-gradient-to-b from-slate-50 to-white p-6 overflow-hidden">
                        
                        <!-- Badges (Absolute) -->
                        <div class="absolute top-3 left-3 z-20 flex gap-2">
                            <span class="px-2 py-1 rounded-lg bg-white/80 backdrop-blur border border-slate-200 text-[10px] font-mono text-slate-500 font-bold shadow-sm">
                                {{ $p->internal_code }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3 z-20">
                            @if($p->stock <= 0)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wide shadow-md">
                                    Agotado
                                </span>
                            @elseif($p->stock <= $p->min_stock)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-600 border border-red-100 text-[10px] font-bold uppercase tracking-wide shadow-sm animate-pulse">
                                    {{ $p->stock }} {{ $p->unit->symbol ?? 'Pz' }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-bold uppercase tracking-wide shadow-sm">
                                    {{ $p->stock }} {{ $p->unit->symbol ?? 'Pz' }}
                                </span>
                            @endif
                        </div>

                        <!-- Main Image -->
                        @if($p->main_image_path)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($p->main_image_path) }}"
                                class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-500 ease-out"
                                loading="lazy" alt="{{ $p->name }}">
                        @else
                            <div class="flex items-center justify-center h-full text-slate-200 group-hover:text-orange-200 transition-colors">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Floating Quick Actions (Hover) -->
                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 translate-y-12 group-hover:translate-y-0 transition-transform duration-300 z-20 flex items-center gap-2 bg-white/90 backdrop-blur-md p-1.5 rounded-xl shadow-lg border border-orange-100">
                             <a href="{{ route('admin.products.edit', $p) }}" class="p-2 rounded-lg text-slate-400 hover:text-orange-500 hover:bg-orange-50 transition-colors" title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <div class="w-px h-4 bg-slate-200"></div>
                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar?')" class="p-2 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors" title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-5 flex-1 flex flex-col pt-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[9px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-md border border-orange-100 uppercase tracking-wider truncate">
                                {{ $p->category->name ?? 'Gral' }}
                            </span>
                             <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider truncate">
                                {{ $p->brand->name ?? '' }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-slate-800 text-sm leading-snug line-clamp-2 min-h-[2.5rem] mb-1 group-hover:text-orange-600 transition-colors">
                            {{ $p->name }}
                        </h3>

                        <div class="mt-auto pt-4 border-t border-slate-50 flex items-end justify-between">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 font-bold uppercase">Precio</span>
                                <span class="text-xl font-black text-slate-900 tracking-tight leading-none">${{ number_format($p->public_price, 2) }}</span>
                            </div>

                            <!-- Big Quick Adjust Button -->
                            <button onclick="Livewire.dispatch('openQuickAdjustment', { productId: {{ $p->id }} })" 
                                class="w-10 h-10 rounded-xl bg-slate-900 text-white shadow-lg shadow-slate-900/30 flex items-center justify-center hover:bg-emerald-500 hover:scale-110 hover:shadow-emerald-500/40 transition-all duration-300"
                                title="Ajuste Rápido (+/-)">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[2rem] border border-dashed border-slate-200">
                <div class="flex flex-col items-center justify-center">
                    <div class="bg-slate-50 p-6 rounded-full mb-4">
                        <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                            </path>
                        </svg>
                    </div>
                    <p class="text-xl font-bold text-slate-900">No se encontraron productos</p>
                    <p class="text-slate-500 mt-2 max-w-sm mx-auto">Parece que no hay nada por aquí. Ajusta los filtros o agrega
                        nuevo inventario.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>

    <livewire:admin.inventory.quick-adjustment />
    
    <x-scanner-modal />
@endsection