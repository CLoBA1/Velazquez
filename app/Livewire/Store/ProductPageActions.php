<?php

namespace App\Livewire\Store;

use App\Services\CartService;
use App\Models\Product;
use Livewire\Component;

class ProductPageActions extends Component
{
    public $productId;
    public $stock;
    public $quantity = 1;

    public function mount($productId, $stock)
    {
        $this->productId = $productId;
        $this->stock = $stock;
    }

    public function increment()
    {
        if ($this->quantity < $this->stock) {
            $this->quantity++;
        }
    }

    public function decrement()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity()
    {
        if ($this->quantity < 1) {
            $this->quantity = 1;
        }

        if ($this->quantity > $this->stock) {
            $this->quantity = $this->stock;
        }
    }

    public function addToCart(CartService $cart)
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->stock
        ]);

        $product = Product::find($this->productId);

        if ($product) {
            try {
                $cart->add($product, $this->quantity);
                $this->dispatch('cart-updated'); // Update Navbar Cart
                $this->dispatch('notify', message: '¡Agregado al carrito!', type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('notify', message: $e->getMessage(), type: 'error');
            }
        }
    }

    public function buyNow(CartService $cart)
    {
        $this->validate([
            'quantity' => 'required|integer|min:1|max:' . $this->stock
        ]);

        $product = Product::find($this->productId);

        if ($product) {
            try {
                $cart->add($product, $this->quantity);
                $this->dispatch('cart-updated');
                return redirect()->route('store.checkout');
            } catch (\Exception $e) {
                $this->dispatch('notify', message: $e->getMessage(), type: 'error');
            }
        }
    }

    public function render()
    {
        return view('livewire.store.product-page-actions');
    }
}
