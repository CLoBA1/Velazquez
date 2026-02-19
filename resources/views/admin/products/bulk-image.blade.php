@extends('admin.layouts.app')

@section('title', 'Asignación Masiva de Imágenes')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                {{ __('Asignación Masiva de Imágenes') }}
            </h2>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">¡Éxito!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 1: Buscar Productos</h3>
                <p class="text-sm text-gray-600 mb-4">Ingrese una palabra clave (ej: "Codo CPVC") para encontrar todos los productos coincidentes.</p>

                <div class="mb-4">
                    <x-input-label for="search_term" :value="__('Término de Búsqueda')" />
                    <x-text-input id="search_term" class="block mt-1 w-full" type="text" name="search_term" placeholder="Ej: Disco corte metal" required autofocus />
                    <p id="search_status" class="text-sm text-gray-500 mt-2"></p>
                </div>

                <div id="preview_area" class="hidden mb-6 border-t pt-4">
                    <h4 class="text-md font-medium text-gray-800 mb-2">Paso 2: Vista Previa (<span id="count_products">0</span> productos encontrados)</h4>
                    <div class="bg-gray-50 p-4 rounded max-h-60 overflow-y-auto">
                        <ul id="product_list" class="list-disc list-inside text-sm text-gray-700">
                            <!-- JS will populate this -->
                        </ul>
                    </div>
                </div>

                <form id="bulk_upload_form" method="POST" action="{{ route('admin.products.bulk-image.update') }}" enctype="multipart/form-data" class="hidden border-t pt-6">
                    @csrf
                    <!-- Hidden field to pass the confirmed search term -->
                    <input type="hidden" name="search_term" id="hidden_search_term">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paso 3: Seleccionar Imagen</h3>
                    
                    <div class="mb-4">
                        <x-input-label for="image" :value="__('Subir Imagen (JPG/PNG/SVG)')" />
                        <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" type="file" name="image" required accept="image/*">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-4 bg-red-600 hover:bg-red-700 confirm-btn">
                            {{ __('APLICAR IMAGEN A TODOS LOS PRODUCTOS LISTADOS') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search_term');
            const statusText = document.getElementById('search_status');
            const previewArea = document.getElementById('preview_area');
            const productList = document.getElementById('product_list');
            const countSpan = document.getElementById('count_products');
            const bulkForm = document.getElementById('bulk_upload_form');
            const hiddenSearchInput = document.getElementById('hidden_search_term');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const term = this.value.trim();
                
                clearTimeout(debounceTimer);
                
                if(term.length < 3) {
                    statusText.textContent = 'Ingrese al menos 3 letras para buscar...';
                    previewArea.classList.add('hidden');
                    bulkForm.classList.add('hidden');
                    return;
                }

                statusText.textContent = 'Buscando...';
                
                debounceTimer = setTimeout(() => {
                    fetch(`{{ route('admin.products.bulk-image.preview') }}?search=${encodeURIComponent(term)}`)
                        .then(response => response.json())
                        .then(data => {
                            productList.innerHTML = '';
                            if(data.count > 0) {
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
            
            bulkForm.addEventListener('submit', function(e) {
                if(!confirm('¿Estás SEGURO de que quieres actualizar la imagen de todos estos productos? Esta acción no se puede deshacer fácilmente.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection