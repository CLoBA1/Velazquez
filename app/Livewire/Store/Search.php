<?php

namespace App\Livewire\Store;

use App\Models\Product;
use Livewire\Component;

class Search extends Component
{
    public $query = '';
    public $results = [];

    public function updatedQuery()
    {
        if (strlen($this->query) >= 2) {
            $this->results = Product::where(function ($q) {
                $q->where('name', 'like', '%' . $this->query . '%')
                    ->orWhere('description', 'like', '%' . $this->query . '%')
                    ->orWhere('internal_code', 'like', '%' . $this->query . '%')
                    ->orWhere('barcode', 'like', '%' . $this->query . '%')
                    ->orWhereHas('brand', function ($brandQ) {
                        $brandQ->where('name', 'like', '%' . $this->query . '%');
                    });
            })
                ->take(5)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function render()
    {
        return view('livewire.store.search');
    }
}
