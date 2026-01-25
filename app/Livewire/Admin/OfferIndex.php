<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class OfferIndex extends Component
{
    use WithPagination;

    // Listen for updates from the modal or other components
    protected $listeners = ['offer-updated' => '$refresh'];

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update([
                'sale_price' => 0, // Set to 0 instead of null
                'sale_deadline' => null,
            ]);

            // Flash message?
            session()->flash('ok', 'Oferta eliminada correctamente.');
        }
    }

    public function render()
    {
        // Logic from OfferController::index
        $offers = Product::where('sale_price', '>', 0)
            ->orderBy('sale_deadline', 'asc')
            ->paginate(10);

        return view('livewire.admin.offer-index', [
            'offers' => $offers
        ]);
    }
}
