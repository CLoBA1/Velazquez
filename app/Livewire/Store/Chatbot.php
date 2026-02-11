<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;

class Chatbot extends Component
{
    public $isOpen = false;
    public $messages = [];
    public $input = '';
    public $isTyping = false;

    public function mount()
    {
        $this->addMessage('bot', '¡Hola! 👋 Bienvenido a Ferretería Velázquez. ¿En qué puedo ayudarte hoy? Puedes preguntarme por productos o seleccionar una opción.');
    }

    public function toggleChat()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function sendMessage()
    {
        if (trim($this->input) === '') {
            return;
        }

        $userText = $this->input;
        $this->input = '';
        $this->addMessage('user', $userText);

        $this->isTyping = true;

        // Simulate a small delay for better UX
        // In a real app we might use a job or just return, but here we process immediately
        $this->handleResponse($userText);

        $this->isTyping = false;
        $this->dispatch('scroll-to-bottom');
    }

    public function handleOption($action)
    {
        $label = match ($action) {
            'hours' => '🕒 Horarios de Atención',
            'location' => '📍 Ubicación',
            'catalog' => '🔨 Ver Catálogo',
            'support' => '💬 Atención al Cliente',
            default => $action
        };

        $this->addMessage('user', $label);
        $this->isTyping = true;
        $this->dispatch('scroll-to-bottom');

        switch ($action) {
            case 'hours':
                $this->addMessage('bot', "Nuestros horarios son:\nLunes a Viernes: 8:00 AM - 6:00 PM\nSábados: 8:00 AM - 2:00 PM\nDomingos: Cerrado");
                break;
            case 'location':
                $this->addMessage('bot', 'Nos encontramos en: 1 Av. Gral. Vicente Guerrero Ixcateopan de Cuauhtémoc, Guerrero frente a la iglesia de Santa Maria de la Asunción. ¡Te esperamos!', [
                    'text' => 'Ver en Google Maps',
                    'url' => 'https://maps.app.goo.gl/jhduUupUtZLKCPRc6'
                ]);
                break;
            case 'catalog':
                $this->addMessage('bot', 'Claro, puedes ver todo nuestro catálogo en línea aquí:', [
                    'text' => 'Ir al Catálogo',
                    'url' => route('store.index')
                ]);
                break;
            case 'support':
                $this->addMessage('bot', '¡Entendido! Te transferiré con un asesor en WhatsApp para atención personalizada.');
                $this->dispatch('open-link', url: 'https://wa.me/527447491902');
                break;
        }
        $this->isTyping = false;
        $this->dispatch('scroll-to-bottom');
    }

    public function handleResponse($text)
    {
        $lowerText = strtolower($text);

        if (str_contains($lowerText, 'hola') || str_contains($lowerText, 'buenos dias') || str_contains($lowerText, 'buenas tardes')) {
            $this->addMessage('bot', '¡Hola! ¿Buscas algún producto en especial?');
            return;
        }

        if (str_contains($lowerText, 'gracias')) {
            $this->addMessage('bot', '¡De nada! Estoy para servirte.');
            return;
        }

        // Product Search Logic
        $products = Product::where('name', 'like', '%' . $text . '%')
            ->orWhere('description', 'like', '%' . $text . '%')
            ->take(3)
            ->get();

        if ($products->count() > 0) {
            $this->addMessage('bot', "Encontré estos productos relacionados con '{$text}':");

            // Add product cards payload
            $this->messages[] = [
                'type' => 'bot',
                'text' => '',
                'products' => $products->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'image_url' => $p->image_url,
                        'price' => $p->offer_price > 0 ? $p->offer_price : $p->price, // Assuming price logic
                        'url' => route('store.show', $p->id)
                    ];
                })->toArray(),
                'time' => now()->format('h:i A')
            ];

            $this->addMessage('bot', "¿Te gustaría ver más detalles de alguno? O escribe 'ver todos' para ir al catálogo.");
        } else {
            $this->addMessage('bot', "Lo siento, no encontré productos exactos para '{$text}'. ¿Podrías intentar con otro nombre o navegar en nuestro catálogo?", [
                'text' => 'Ir al Catálogo',
                'url' => route('store.index')
            ]);
        }
    }

    protected function addMessage($type, $text, $link = null)
    {
        $this->messages[] = [
            'type' => $type,
            'text' => $text,
            'link' => $link,
            'time' => now()->format('h:i A')
        ];
    }

    public function render()
    {
        return view('livewire.store.chatbot');
    }
}
