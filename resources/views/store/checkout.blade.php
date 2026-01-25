@extends('layouts.store')

@section('title', 'Finalizar Compra | Ferretería Velázquez')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-3xl font-black text-slate-900 mb-8 tracking-tight text-center">Finalizar Compra</h1>

            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 sm:p-12">

                    @if(session('error'))
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('store.checkout.process') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Contact Info -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span
                                    class="bg-orange-100 text-orange-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">1</span>
                                Datos de Contacto
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="text-sm font-bold text-slate-700 ml-1">Nombre Completo</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name ?? old('name') }}"
                                        required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                                    @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="text-sm font-bold text-slate-700 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ auth()->user()->email ?? old('email') }}"
                                        required
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                                    @error('email') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="space-y-1 sm:col-span-2">
                                    <label class="text-sm font-bold text-slate-700 ml-1">Teléfono</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" required
                                        placeholder="Para coordinar la entrega"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                                    @error('phone') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Shipping Address -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span
                                    class="bg-orange-100 text-orange-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">2</span>
                                Dirección de Entrega
                            </h3>
                            <div class="space-y-1">
                                <textarea name="address" rows="3" required
                                    placeholder="Calle, Número, Colonia, Ciudad, Código Postal..."
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">{{ old('address') }}</textarea>
                                @error('address') <span class="text-red-500 text-xs font-bold">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-sm font-bold text-slate-700 ml-1">Referencias / Notas (Opcional)</label>
                                <input type="text" name="notes" value="{{ old('notes') }}"
                                    placeholder="Entre calles, color de fachada, etc."
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- Payment -->
                        <div class="space-y-4">
                            <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                                <span
                                    class="bg-orange-100 text-orange-600 w-8 h-8 rounded-full flex items-center justify-center text-sm">3</span>
                                Método de Pago
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label
                                    class="border-2 border-gray-100 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 rounded-xl p-4 cursor-pointer transition-all hover:border-orange-200">
                                    <input type="radio" name="payment_method" value="cash" checked class="hidden">
                                    <span class="font-bold text-slate-800 block mb-1">Pago contra Entrega</span>
                                    <span class="text-xs text-slate-500">Pagas en efectivo al recibir tu pedido.</span>
                                </label>
                                <label
                                    class="border-2 border-gray-100 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50 rounded-xl p-4 cursor-pointer transition-all hover:border-orange-200">
                                    <input type="radio" name="payment_method" value="transfer" class="hidden">
                                    <span class="font-bold text-slate-800 block mb-1">Transferencia</span>
                                    <span class="text-xs text-slate-500">Te enviaremos los datos bancarios.</span>
                                </label>
                            </div>
                        </div>

                        <!-- Summary & Submit -->
                        <div class="bg-gray-50 -mx-8 -mb-12 p-8 sm:p-12 mt-8 border-t border-gray-100">
                            <div class="flex justify-between items-center mb-6 text-xl">
                                <span class="font-bold text-slate-700">Total a Pagar</span>
                                <span class="font-black text-slate-900 text-3xl">${{ number_format($total, 2) }}</span>
                            </div>
                            <button type="submit"
                                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-xl shadow-xl shadow-slate-900/20 transition-all hover:scale-[1.01] transform flex items-center justify-center gap-3">
                                <span>Confirmar Pedido</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection