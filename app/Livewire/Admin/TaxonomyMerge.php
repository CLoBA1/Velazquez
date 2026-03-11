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

    // Auto-compress
    public $duplicateGroups = [];
    public $confirmingAutoCompress = false;
    public $autoCompressResults = [];
    public $showingAutoCompress = false;

    public function mount()
    {
        $this->loadData();
    }

    public function updatedType()
    {
        $this->reset(['sourceId', 'targetId', 'confirmingMerge', 'confirmingAutoCompress', 'autoCompressResults', 'showingAutoCompress', 'duplicateGroups']);
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
                $sourceCategories = Category::where('family_id', $source->id)->get();
                foreach ($sourceCategories as $sourceCategory) {
                    $targetCategoryExists = Category::where('family_id', $target->id)
                        ->where('name', $sourceCategory->name)
                        ->first();

                    if ($targetCategoryExists) {
                        // Collision: Move products to the existing category in target family and delete the duplicate
                        Product::where('category_id', $sourceCategory->id)
                            ->update(['category_id' => $targetCategoryExists->id]);
                        $sourceCategory->delete();
                    } else {
                        // No collision: safe to just change the family of the category
                        $sourceCategory->update(['family_id' => $target->id]);
                    }
                }

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

    // ─────────── AUTO-COMPRESS ───────────

    /**
     * Scans the database and finds categories that share the exact same name.
     * Groups them and shows a preview before executing.
     */
    public function previewAutoCompress()
    {
        $this->duplicateGroups = [];
        $this->showingAutoCompress = true;
        $this->confirmingAutoCompress = false;
        $this->autoCompressResults = [];

        if ($this->type === 'category') {
            // Find all category names that appear more than once
            $duplicateNames = DB::table('categories')
                ->select('name', DB::raw('COUNT(*) as count'))
                ->groupBy('name')
                ->having('count', '>', 1)
                ->orderBy('name')
                ->get();

            foreach ($duplicateNames as $dup) {
                $cats = Category::with('family')
                    ->withCount('products')
                    ->where('name', $dup->name)
                    ->orderByDesc('products_count') // Keep the one with most products
                    ->get();

                $this->duplicateGroups[] = [
                    'name'   => $dup->name,
                    'count'  => $dup->count,
                    'items'  => $cats->map(fn($c) => [
                        'id'             => $c->id,
                        'family'         => $c->family?->name ?? '—',
                        'products_count' => $c->products_count,
                    ])->toArray(),
                    'keep_id' => $cats->first()->id, // Will keep the one with most products
                ];
            }
        } else {
            // For families, show all families so users know what they are selecting
            $this->duplicateGroups = []; // No auto-detect for families (use manual merge)
            session()->flash('info', 'Para familias usa las opciones manuales de arriba. El auto-comprimir solo aplica a Categorías.');
            $this->showingAutoCompress = false;
        }
    }

    /**
     * Executes the auto-compress: merges all duplicate category names
     * into the one with the most products (keeps that one, deletes all others).
     */
    public function executeAutoCompress()
    {
        if (empty($this->duplicateGroups)) {
            return;
        }

        $this->autoCompressResults = [];
        $mergedCount = 0;
        $errors = [];

        DB::beginTransaction();
        try {
            foreach ($this->duplicateGroups as $group) {
                $keepId = $group['keep_id'];
                $itemIds = collect($group['items'])->pluck('id')->toArray();

                foreach ($itemIds as $catId) {
                    if ($catId == $keepId) continue; // Skip the one we're keeping

                    $source = Category::find($catId);
                    if (!$source) continue;

                    // Move all products to the category we're keeping
                    $moved = Product::where('category_id', $catId)->count();
                    Product::where('category_id', $catId)->update(['category_id' => $keepId]);
                    $source->delete();
                    $mergedCount++;
                    $sourceFamilyName = $source->family ? $source->family->name : '?';
                    $this->autoCompressResults[] = "✓ Fusionado: '{$source->name}' ({$sourceFamilyName}) → Categoría ID {$keepId}. Productos movidos: {$moved}";
                }
            }

            DB::commit();
            $this->autoCompressResults[] = "──────────";
            $this->autoCompressResults[] = "🎉 Proceso completado: {$mergedCount} categorías duplicadas eliminadas.";

        } catch (\Exception $e) {
            DB::rollBack();
            $this->autoCompressResults[] = "❌ Error: " . $e->getMessage();
        }

        $this->confirmingAutoCompress = false;
        $this->duplicateGroups = [];
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.taxonomy-merge')
            ->layout('admin.layouts.app');
    }
}
