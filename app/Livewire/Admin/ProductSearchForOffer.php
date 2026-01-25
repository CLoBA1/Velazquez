<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class ProductSearchForOffer extends Component
{
    public $search = '';

    public function selectProduct($productId)
    {
        $this->dispatch('edit-offer', productId: $productId);
        $this->search = ''; // Clear search
    }

    public function render()
    {
        $products = [];
        if (strlen($this->search) > 2) {
            $products = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('internal_code', 'like', '%' . $this->search . '%')
                ->limit(10)
                ->get();
        }

        return view('livewire.admin.product-search-for-offer', [
            'products' => $products
        ]);
    }
}
