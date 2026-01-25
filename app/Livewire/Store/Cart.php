<?php

namespace App\Livewire\Store;

use App\Services\CartService;
use Livewire\Component;

class Cart extends Component
{
    public $cartItems = [];
    public $total = 0;

    public function mount(CartService $cart)
    {
        $this->refreshCart($cart);
    }

    public function refreshCart(CartService $cart)
    {
        $this->cartItems = $cart->getContent();
        $this->total = $cart->total();
    }

    public function updateQuantity(CartService $cart, $productId, $quantity)
    {
        $cart->update($productId, $quantity);
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
    }

    public function removeItem(CartService $cart, $productId)
    {
        $cart->remove($productId);
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
    }

    public function clearCart(CartService $cart)
    {
        $cart->clear();
        $this->refreshCart($cart);
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        return view('livewire.store.cart');
    }
}
