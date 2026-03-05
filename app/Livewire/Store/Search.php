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
            $this->results = Product::searchFuzzy($this->query)
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
