<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Family;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class TaxonomyMerge extends Component
{
    public $type = 'category'; // 'category' or 'family'

    // Form inputs
    public $sourceId = '';
    public $targetId = '';

    // Data for dropdowns
    public $families = [];
    public $categories = [];

    // Confirmation logic
    public $confirmingMerge = false;

    public function mount()
    {
        $this->loadData();
    }

    public function updatedType()
    {
        $this->reset(['sourceId', 'targetId', 'confirmingMerge']);
        $this->loadData();
    }

    private function loadData()
    {
        if ($this->type === 'family') {
            $this->families = Family::withCount('products')->orderBy('name')->get();
        } else {
            $this->categories = Category::with('family')->withCount('products')
                ->get()
                ->sortBy(function ($cat) {
                    return $cat->family->name . ' - ' . $cat->name;
                });
        }
    }

    public function confirmMerge()
    {
        $this->validate([
            'sourceId' => 'required',
            'targetId' => 'required|different:sourceId',
        ], [
            'sourceId.required' => 'Debes seleccionar un origen.',
            'targetId.required' => 'Debes seleccionar un destino.',
            'targetId.different' => 'El origen y el destino no pueden ser el mismo.',
        ]);

        $this->confirmingMerge = true;
    }

    public function executeMerge()
    {
        if (!$this->confirmingMerge)
            return;

        DB::beginTransaction();
        try {
            if ($this->type === 'family') {
                $source = Family::findOrFail($this->sourceId);
                $target = Family::findOrFail($this->targetId);

                // Move all categories to new family
                Category::where('family_id', $source->id)->update(['family_id' => $target->id]);

                $source->delete();
                session()->flash('message', "Familia '{$source->name}' fusionada en '{$target->name}' correctamente.");

            } else {
                $source = Category::findOrFail($this->sourceId);
                $target = Category::findOrFail($this->targetId);

                // Move all products to new category
                Product::where('category_id', $source->id)->update(['category_id' => $target->id]);

                $source->delete();
                session()->flash('message', "Categoría '{$source->name}' fusionada en '{$target->name}' correctamente. Productos movidos.");
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Error al fusionar: " . $e->getMessage());
        }

        $this->reset(['sourceId', 'targetId', 'confirmingMerge']);
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.taxonomy-merge')
            ->layout('admin.layouts.app');
    }
}
