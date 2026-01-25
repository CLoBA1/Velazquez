<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;
use App\Models\Product;

use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class MovementCreate extends Component
{
    public $productId;
    public $type = 'adjustment_add';
    public $quantity;
    public $notes;

    // For product search
    public $search = '';
    public $results = [];

    public function updatedSearch()
    {
        if (strlen($this->search) > 2) {
            $this->results = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('internal_code', 'like', '%' . $this->search . '%')
                ->take(10)
                ->get();
        } else {
            $this->results = [];
        }
    }

    public function selectProduct($id)
    {
        $this->productId = $id;
        $product = Product::find($id);
        $this->search = $product->name . ' (' . $product->internal_code . ')';
        $this->results = [];
    }

    public function save()
    {
        $this->validate([
            'productId' => 'required|exists:products,id',
            'type' => 'required|in:adjustment_add,adjustment_sub,return,purchase,sale', // usually adjustment or purchase/return for manual
            'quantity' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string|max:255',
        ]);

        $product = Product::find($this->productId);

        $product->adjustStock(
            $this->quantity,
            $this->type,
            $this->notes
        );

        session()->flash('success', 'Movimiento registrado correctamente.');

        $this->reset(['productId', 'type', 'quantity', 'notes', 'search', 'results']);
        $this->type = 'adjustment_add'; // Reset to default

        return redirect()->route('admin.inventory.movements');
    }

    public function render()
    {
        return view('livewire.admin.inventory.movement-create');
    }
}
