<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class OfferManagerModal extends Component
{
    public $show = false;
    public $product;
    public $sale_price;
    public $sale_deadline;

    // Listen for events
    #[\Livewire\Attributes\On('edit-offer')]
    public function open($productId)
    {
        $this->product = Product::find($productId);
        if ($this->product) {
            $this->sale_price = $this->product->sale_price > 0 ? $this->product->sale_price : null;
            $this->sale_deadline = $this->product->sale_deadline ? $this->product->sale_deadline->format('Y-m-d\TH:i') : null;
            $this->show = true;
        }
    }

    public function save()
    {
        $this->validate([
            'sale_price' => 'required|numeric|min:0|lt:product.public_price',
            'sale_deadline' => 'nullable|date|after:now',
        ], [
            'sale_price.lt' => 'El precio de oferta debe ser menor al precio normal ($' . ($this->product ? number_format((float) $this->product->public_price, 2) : 0) . ')',
        ]);

        $this->product->update([
            'sale_price' => $this->sale_price,
            'sale_deadline' => $this->sale_deadline ?: null,
        ]);

        $this->show = false;

        $this->dispatch('offer-updated');

        // Refresh page to show updated list (simplest for now, though we could just refresh list component)
        return redirect()->route('admin.offers.index')->with('ok', 'Oferta guardada correctamente.');
    }

    public function close()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.admin.offer-manager-modal');
    }
}
