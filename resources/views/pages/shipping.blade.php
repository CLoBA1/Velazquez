@extends('layouts.store')

@section('title', 'Envíos - Ferretería Velázquez')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="text-3xl font-bold text-dark mb-4">Política de Envíos</h1>
                    <p class="text-gray-600 mb-4">
                        En Ferretería Velázquez nos comprometemos a entregar tus productos de manera segura y eficiente.
                    </p>
                    <h2 class="text-xl font-bold text-dark mt-6 mb-2">Tiempos de Entrega</h2>
                    <ul class="list-disc pl-5 text-gray-600 space-y-2">
                        <li>CDMX y Área Metropolitana: 24 a 48 horas hábiles.</li>
                        <li>Resto del país: 3 a 5 días hábiles.</li>
                    </ul>
                    <h2 class="text-xl font-bold text-dark mt-6 mb-2">Costos de Envío</h2>
                    <p class="text-gray-600">
                        El envío es gratuito en compras superiores a $2,000 MXN. Para pedidos menores, el costo se calculará
                        al finalizar la compra.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection