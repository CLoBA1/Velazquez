<?php

namespace App\Livewire\Store;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class NavbarCart extends Component
{
    public $count = 0;

    public function mount(CartService $cart)
    {
        $this->updateCount($cart);
    }

    #[On('cart-updated')]
    public function updateCart(CartService $cart)
    {
        $this->updateCount($cart);
    }

    public function updateCount(CartService $cart)
    {
        $this->count = $cart->count();
    }

    public function render()
    {
        return view('livewire.store.navbar-cart');
    }
}
