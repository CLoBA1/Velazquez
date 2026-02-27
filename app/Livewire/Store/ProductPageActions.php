<?php

namespace App\Livewire\Store;

use App\Services\CartService;
use App\Models\Product;
use Livewire\Component;

class ProductPageActions extends Component
{
    public $product;
    public $productId;
    public $stock;
    public $quantity = 1;

    // Variant Selection
    public $selectedUnitId = null;
    public $currentPrice = 0;
    public $variants = [];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->stock = $product->stock;
        $this->currentPrice = $product->public_price;

        // Load presentations if available
        if ($product->relationLoaded('units')) {
            $this->variants = $product->units->toArray();
        } elseif ($product->units()->exists()) {
            $this->variants = $product->units()->with('unit')->get()->toArray();
        }
    }

    public function updatedSelectedUnitId($value)
    {
        if (empty($value)) {
            $this->currentPrice = $this->product->public_price;
            $this->stock = $this->product->stock;
            $this->quantity = 1;
        } else {
            $variant = collect($this->variants)->firstWhere('id', (int) $value);
            if ($variant) {
                $this->currentPrice = $variant['public_price'];
                if ($variant['conversion_factor'] > 0) {
                    $this->stock = floor($this->product->stock / $variant['conversion_factor']);
                }
            }
        }
        $this->updatedQuantity(); // trigger min/max validation
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
            'quantity' => 'required|integer|min:1|max:' . max(1, $this->stock) // Prevent validation crash if stock 0
        ]);

        $product = Product::find($this->productId);

        if ($product) {
            try {
                $unitIdInt = $this->selectedUnitId ? (int) $this->selectedUnitId : null;
                $cart->add($product, $this->quantity, $unitIdInt);
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
            'quantity' => 'required|integer|min:1|max:' . max(1, $this->stock)
        ]);

        $product = Product::find($this->productId);

        if ($product) {
            try {
                $unitIdInt = $this->selectedUnitId ? (int) $this->selectedUnitId : null;
                $cart->add($product, $this->quantity, $unitIdInt);
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
