@extends('admin.layouts.app')

@section('title', 'Centro de Operaciones Masivas')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Centro de Operaciones Masivas') }}
            </h2>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 1: Buscar Productos</h3>
                <p class="text-sm text-gray-600 mb-4">Ingrese una palabra clave (ej: "Codo CPVC") para encontrar todos los
                    productos coincidentes.</p>

                <div class="mb-4">
                    <x-input-label for="search_term" :value="__('Término de Búsqueda')" />
                    <x-text-input id="search_term" class="block mt-1 w-full" type="text" name="search_term"
                        placeholder="Ej: Disco corte metal" required autofocus />
                    <p id="search_status" class="text-sm text-gray-500 mt-2"></p>
                </div>

                <div id="preview_area" class="hidden mb-6 border-t pt-4">
                    <h4 class="text-md font-medium text-gray-800 mb-2">Paso 2: Vista Previa (<span
                            id="count_products">0</span> productos encontrados)</h4>
                    <div class="bg-gray-50 p-4 rounded max-h-60 overflow-y-auto">
                        <ul id="product_list" class="list-disc list-inside text-sm text-gray-700">
                            <!-- JS will populate this -->
                        </ul>
                    </div>
                </div>

                <form id="bulk_upload_form" method="POST" action="{{ route('admin.bulk-actions.update') }}"
                    enctype="multipart/form-data" class="hidden border-t pt-6">
                    @csrf
                    <!-- Hidden field to pass the confirmed search term -->
                    <input type="hidden" name="search_term" id="hidden_search_term">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 3: Seleccionar Acción</h3>

                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="cursor-pointer border p-4 rounded-lg hover:bg-gray-50 flex items-center gap-3">
                            <input type="radio" name="action_type" value="assign_image" checked
                                class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="block font-bold">Asignar Imagen</span>
                                <span class="text-xs text-gray-500">Sube una nueva foto para todos.</span>
                            </div>
                        </label>

                        <label class="cursor-pointer border p-4 rounded-lg hover:bg-gray-50 flex items-center gap-3">
                            <input type="radio" name="action_type" value="assign_brand"
                                class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="block font-bold">Cambiar Marca</span>
                                <span class="text-xs text-gray-500">Actualiza la marca de todos.</span>
                            </div>
                        </label>

                        <label class="cursor-pointer border p-4 rounded-lg hover:bg-gray-50 flex items-center gap-3">
                            <input type="radio" name="action_type" value="remove_image"
                                class="text-red-600 focus:ring-red-500">
                            <div>
                                <span class="block font-bold text-red-600">Eliminar Imagen</span>
                                <span class="text-xs text-gray-500">Quita la foto actual.</span>
                            </div>
                        </label>
                    </div>

                    <!-- INPUT: Image Upload -->
                    <div id="input_image" class="mb-4 action-input">
                        <x-input-label for="image" :value="__('Subir Imagen (JPG/PNG/SVG)')" />
                        <input id="image"
                            class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                            type="file" name="image" accept="image/*">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <!-- INPUT: Brand Select -->
                    <div id="input_brand" class="mb-4 action-input hidden">
                        <x-input-label for="brand_id" :value="__('Seleccionar Nueva Marca')" />
                        <select id="brand_id" name="brand_id"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">Seleccione una marca...</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('brand_id')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4 bg-indigo-600 hover:bg-indigo-700 confirm-btn">
                            {{ __('EJECUTAR ACCIÓN MASIVA') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search_term');
            const statusText = document.getElementById('search_status');
            const previewArea = document.getElementById('preview_area');
            const productList = document.getElementById('product_list');
            const countSpan = document.getElementById('count_products');
            const bulkForm = document.getElementById('bulk_upload_form');
            const hiddenSearchInput = document.getElementById('hidden_search_term');

            // Action inputs
            const actionRadios = document.querySelectorAll('input[name="action_type"]');
            const inputImage = document.getElementById('input_image');
            const inputBrand = document.getElementById('input_brand');

            // Handle Action Switch
            actionRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    // Hide all first
                    inputImage.classList.add('hidden');
                    inputBrand.classList.add('hidden');

                    // Reset required attributes to avoid browser validation errors on hidden fields
                    document.getElementById('image').removeAttribute('required');
                    document.getElementById('brand_id').removeAttribute('required');

                    if (this.value === 'assign_image') {
                        inputImage.classList.remove('hidden');
                        document.getElementById('image').setAttribute('required', 'required');
                    } else if (this.value === 'assign_brand') {
                        inputBrand.classList.remove('hidden');
                        document.getElementById('brand_id').setAttribute('required', 'required');
                    } else if (this.value === 'remove_image') {
                        // No extra input needed
                    }
                });
            });

            // Search Logic
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                const term = this.value.trim();

                clearTimeout(debounceTimer);

                if (term.length < 3) {
                    statusText.textContent = 'Ingrese al menos 3 letras para buscar...';
                    previewArea.classList.add('hidden');
                    bulkForm.classList.add('hidden');
                    return;
                }

                statusText.textContent = 'Buscando...';

                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('admin.bulk-actions.preview') }}?search=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            productList.innerHTML = '';
                            if (data.count > 0) {
                                data.products.forEach(p => {
                                    const li = document.createElement('li');
                                    li.textContent = `${p.internal_code} - ${p.name}`;
                                    productList.appendChild(li);
                                });
                                countSpan.textContent = data.count;
                                previewArea.classList.remove('hidden');
                                bulkForm.classList.remove('hidden');
                                hiddenSearchInput.value = term;
                                statusText.textContent = `Se encontraron ${data.count} coincidencias.`;
                            } else {
                                statusText.textContent = 'No se encontraron productos con ese nombre.';
                                previewArea.classList.add('hidden');
                                bulkForm.classList.add('hidden');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            statusText.textContent = 'Error al buscar productos.';
                        });
                }, 500);
            });

            bulkForm.addEventListener('submit', function (e) {
                const action = document.querySelector('input[name="action_type"]:checked').value;
                let msg = '¿Estás SEGURO de que quieres actualizar estos productos?';

                if (action === 'remove_image') {
                    msg = 'ATENCIÓN: Estás a punto de ELIMINAR la imagen de todos los productos listados. ¿Continuar?';
                }

                if (!confirm(msg)) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection