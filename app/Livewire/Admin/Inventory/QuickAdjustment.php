<?php

namespace App\Livewire\Admin\Inventory;

use Livewire\Component;
use App\Models\Product;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Auth;

class QuickAdjustment extends Component
{
    public $isOpen = false;
    public $product = null;
    public $amount = '';
    public $notes = 'Ajuste rápido desde catálogo';
    public $type = 'adjustment'; // adjustment, purchase, return

    protected $listeners = [
        'openQuickAdjustment' => 'open',
        'scanCode' => 'handleScan'
    ];

    public function handleScan($code)
    {
        // Find product by Internal Code or Barcode (if you have one)
        // Adjust column name if you match checks elsewhere. 
        // Assuming 'internal_code' is what scanner sends or User types.
        $product = Product::where('internal_code', $code)->first();

        if ($product) {
            $this->open($product->id);
            // Optionally play a sound or notify
        }
    }

    public function open($productId)
    {
        $this->product = Product::find($productId);
        $this->amount = '';
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['product', 'amount']);
    }

    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric|not_in:0',
            'notes' => 'nullable|string|max:255',
        ]);

        if (!$this->product)
            return;

        $quantity = (float) $this->amount;

        // Calculate previous and new stock
        $oldStock = $this->product->stock;
        $newStock = $oldStock + $quantity;

        // Update Product Stock
        $this->product->stock = $newStock;
        $this->product->save();

        // Create Movement Record
        InventoryMovement::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'type' => $quantity > 0 ? 'adjustment_add' : 'adjustment_sub',
            'quantity' => abs($quantity),
            'previous_stock' => $oldStock,
            'new_stock' => $newStock,
            'notes' => $this->notes
        ]);

        session()->flash('ok', "Stock actualizado: {$this->product->name} (Nuevo: {$newStock})");

        $this->close();

        // Refresh the parent page or emit event
        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.inventory.quick-adjustment');
    }
}
