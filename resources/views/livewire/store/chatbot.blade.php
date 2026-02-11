<div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-4 font-sans"
    style="position: fixed; bottom: 24px; right: 24px; z-index: 50;"
    x-data="{ scrollToBottom() { $nextTick(() => { $refs.chatContainer.scrollTop = $refs.chatContainer.scrollHeight }) } }"
    @scroll-to-bottom.window="scrollToBottom()">

    <!-- Chat Window -->
    @if($isOpen)
        <div x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 scale-95"
             class="bg-white w-80 sm:w-96 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col h-[500px] max-h-[80vh]">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-4 flex items-center justify-between shrink-0" style="background: linear-gradient(to right, #0f172a, #1e293b); color: white;">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white relative">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-1.07 3.97-2.9 5.4z"/></svg>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-slate-900 rounded-full"></span>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-sm">Asistente Inteligente</h3>
                        <p class="text-slate-400 text-xs">En línea ahora</p>
                    </div>
                </div>
                <button wire:click="toggleChat" class="text-slate-400 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Messages Area -->
            <div x-ref="chatContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50 scroll-smooth">
                @foreach($messages as $msg)
                    <div class="{{ $msg['type'] === 'user' ? 'flex justify-end' : 'flex justify-start' }}">
                        <div class="{{ $msg['type'] === 'user' ? 'bg-secondary text-dark rounded-tr-none' : 'bg-white border border-gray-100 text-slate-700 rounded-tl-none shadow-sm' }} max-w-[85%] rounded-2xl px-4 py-3 text-sm relative group">
                            
                            @if(!empty($msg['text']))
                                <p class="whitespace-pre-line leading-relaxed">{{ $msg['text'] }}</p>
                            @endif

                             <!-- Product Cards -->
                             @if(isset($msg['products']) && count($msg['products']) > 0)
                                <div class="mt-3 space-y-3">
                                    @foreach($msg['products'] as $product)
                                        <div class="bg-gray-50 rounded-lg p-2 flex gap-3 border border-gray-200 hover:border-primary transition-colors cursor-pointer" onclick="window.open('{{ route('store.show', $product['id']) }}', '_blank')">
                                            @if($product['image_url'])
                                                <img src="{{ $product['image_url'] }}" class="w-12 h-12 object-contain bg-white rounded-md border border-gray-100">
                                            @else
                                                <div class="w-12 h-12 bg-white rounded-md border border-gray-100 flex items-center justify-center text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-xs text-slate-900 truncate">{{ $product['name'] }}</h4>
                                                <p class="text-xs text-secondary font-bold mt-1">${{ number_format($product['price'], 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                             @endif
                            
                            <!-- Optional Link -->
                            @if(isset($msg['link']))
                                <a href="{{ $msg['link']['url'] }}" target="_blank" class="block mt-3 text-primary font-bold hover:underline border-t border-gray-100 pt-2 flex items-center gap-1">
                                    <span>{{ $msg['link']['text'] }}</span>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            @endif

                            <span class="text-[10px] opacity-60 mt-1 block w-full text-right">{{ $msg['time'] }}</span>
                        </div>
                    </div>
                @endforeach

                <!-- Typing Indicator -->
                @if($isTyping)
                    <div class="flex justify-start">
                        <div class="bg-white border border-gray-100 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm flex items-center gap-1 w-16 h-10">
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-100"></span>
                            <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-200"></span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Inputs / Options -->
            <div class="p-4 bg-white border-t border-gray-100 shrink-0">
                <!-- Text Input -->
                <div class="relative mb-3">
                    <input type="text" wire:model="input" wire:keydown.enter="sendMessage" 
                        placeholder="Escribe tu consulta..." 
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl py-3 pl-4 pr-10 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
                    
                    <button wire:click="sendMessage" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-primary hover:bg-blue-50 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </div>

                <!-- Quick Actions (Horizontal Scroll if needed, or Grid) -->
                <div class="flex gap-2 overflow-x-auto pb-2 hide-scrollbar">
                    <button wire:click="handleOption('hours')" class="whitespace-nowrap px-3 py-2 bg-gray-50 hover:bg-blue-50 text-xs font-bold text-slate-600 hover:text-primary rounded-lg border border-gray-200 hover:border-blue-100 transition-colors">
                        🕒 Horarios
                    </button>
                    <button wire:click="handleOption('location')" class="whitespace-nowrap px-3 py-2 bg-gray-50 hover:bg-blue-50 text-xs font-bold text-slate-600 hover:text-primary rounded-lg border border-gray-200 hover:border-blue-100 transition-colors">
                        📍 Ubicación
                    </button>
                    <button wire:click="handleOption('support')" class="whitespace-nowrap px-3 py-2 bg-gray-50 hover:bg-blue-50 text-xs font-bold text-slate-600 hover:text-primary rounded-lg border border-gray-200 hover:border-blue-100 transition-colors">
                        💬 Atención al Cliente
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Floating Trigger Button -->
    <button wire:click="toggleChat" 
            class="{{ $isOpen ? 'scale-0 opacity-0' : 'scale-100 opacity-100' }} bg-slate-900 hover:bg-black text-white rounded-full p-4 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center group w-14 h-14 relative z-50">
        <!-- Notification Dot -->
        <span class="absolute top-0 right-0 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        
        <!-- Icon -->
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        
        <!-- Tooltip -->
        <div class="absolute right-full mr-4 bg-white text-slate-900 px-4 py-2 rounded-xl shadow-md text-sm font-bold whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none border border-gray-100">
            ¿Necesitas ayuda?
            <!-- Arrow -->
            <span class="absolute top-1/2 -right-1.5 w-3 h-3 bg-white transform -translate-y-1/2 rotate-45 border-t border-r border-gray-100"></span>
        </div>
    </button>
</div>
