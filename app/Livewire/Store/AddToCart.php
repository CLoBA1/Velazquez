<?php

namespace App\Livewire\Store;

use App\Services\CartService;
use App\Models\Product;
use Livewire\Component;

class AddToCart extends Component
{
    public $productId;
    public $quantity = 1;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart(CartService $cart)
    {
        $product = Product::find($this->productId);

        if ($product) {
            try {
                $cart->add($product, $this->quantity);
                $this->dispatch('cart-updated');
                $this->dispatch('notify', message: 'Producto agregado al carrito', type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('notify', message: $e->getMessage(), type: 'error');
            }
        }
    }

    public function render()
    {
        return view('livewire.store.add-to-cart');
    }
}
