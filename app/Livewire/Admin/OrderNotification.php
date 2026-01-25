<?php

namespace App\Livewire\Admin;

use App\Models\Sale;
use Livewire\Component;

class OrderNotification extends Component
{
    public $lastOrderId;

    public function mount()
    {
        $this->lastOrderId = Sale::where('source', 'web')->max('id') ?? 0;
    }

    public function checkOrders()
    {
        $newMaxId = Sale::where('source', 'web')->max('id') ?? 0;

        if ($newMaxId > $this->lastOrderId) {
            $this->lastOrderId = $newMaxId;
            $this->dispatch('notify', message: '¡Nueva Venta Web Recibida! 🛒', type: 'info');
        }
    }

    public function render()
    {
        return view('livewire.admin.order-notification');
    }
}
