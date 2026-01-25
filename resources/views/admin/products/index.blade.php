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
             }" x-init="filterCategories()">

        <form method="GET" action="{{ route('admin.products.index') }}"
            class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Buscar</label>
                <div class="relative group">
                    <input name="search" value="{{ request('search') }}" placeholder="Nombre, código, SKU..."
                        class="w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-10 pr-4 text-sm text-slate-800 focus:border-orange-500 focus:bg-white focus:ring-2 focus:ring-orange-100 transition-all font-medium">
                    <svg class="absolute left-3 top-3 w-5 h-5 text-slate-400 group-focus-within:text-orange-500 transition-colors"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
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
                class="group bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-orange-500/10 hover:border-orange-100 transition-all duration-300 flex flex-col overflow-hidden relative hover:-translate-y-1">

                <!-- Image & Overlay -->
                <div
                    class="relative aspect-[4/3] bg-slate-50 overflow-hidden border-b border-slate-100 group-hover:bg-white transition-colors">
                    @if($p->main_image_path)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($p->main_image_path) }}"
                            class="w-full h-full object-contain p-8 transition-transform duration-700 group-hover:scale-110 mix-blend-multiply"
                            loading="lazy" alt="{{ $p->name }}">
                    @else
                        <div class="flex items-center justify-center h-full text-slate-200">
                            <svg class="w-20 h-20 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif

                    <!-- Stock Badge (Absolute) -->
                    <div class="absolute top-4 right-4 z-10">
                        @if($p->stock <= 0)
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-900 text-white text-[10px] font-bold uppercase tracking-wide shadow-lg">
                                Agotado
                            </span>
                        @elseif($p->stock <= $p->min_stock)
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-50 text-red-600 border border-red-100 text-[10px] font-bold uppercase tracking-wide shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                </span>
                                {{ $p->stock }} {{ $p->unit->symbol ?? 'Pz' }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-bold uppercase tracking-wide shadow-sm">
                                {{ $p->stock }} {{ $p->unit->symbol ?? 'Pz' }}
                            </span>
                        @endif
                    </div>

                    <!-- Code -->
                    <div class="absolute top-4 left-4 z-10">
                        <span
                            class="px-2.5 py-1 rounded-lg bg-white/90 backdrop-blur border border-slate-200 text-[10px] font-mono text-slate-500 font-bold shadow-sm">
                            {{ $p->internal_code }}
                        </span>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-6 flex-1 flex flex-col">
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <span
                                class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2.5 py-1 rounded-full border border-orange-100 truncate max-w-[60%]">
                                {{ $p->category->name ?? 'General' }}
                            </span>
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider truncate max-w-[35%]">
                                {{ $p->brand->name ?? 'N/A' }}
                            </span>
                        </div>
                        <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-2 min-h-[3rem] group-hover:text-orange-600 transition-colors"
                            title="{{ $p->name }}">
                            {{ $p->name }}
                        </h3>
                    </div>

                    <div class="mt-auto pt-5 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">Público</span>
                            <div class="flex items-baseline gap-1">
                                <span class="text-xs font-medium text-slate-400">$</span>
                                <span
                                    class="text-2xl font-black text-slate-900 tracking-tight">{{ number_format($p->public_price, 2) }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.edit', $p) }}"
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-white hover:bg-orange-500 transition-all group/edit"
                                title="Editar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                            </a>

                            <form method="POST" action="{{ route('admin.products.destroy', $p) }}" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('¿Eliminar producto?')"
                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:text-white hover:bg-red-500 transition-all"
                                    title="Eliminar">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </form>
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
@endsection