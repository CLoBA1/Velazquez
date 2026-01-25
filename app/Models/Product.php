<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'internal_code',
        'supplier_sku',
        'barcode',
        'category_id',
        'brand_id',
        'unit_id',
        'name',
        'description',
        'slug',
        'main_image_path',
        'cost_price',
        'sale_price',
        'public_price',
        'mid_wholesale_price',
        'wholesale_price',
        'stock',
        'min_stock',
        'taxes_percent',
        'sale_deadline',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'public_price' => 'decimal:2',
        'mid_wholesale_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'taxes_percent' => 'decimal:2',
        'sale_deadline' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    /**
     * Adjust product stock and record movement
     * 
     * @param float $quantity Amount to adjust (always positive)
     * @param string $type enum: purchase, sale, adjustment_add, adjustment_sub, return
     * @param string|null $notes
     * @param int|null $userId Defaults to auth user
     */
    public function adjustStock(float $quantity, string $type, ?string $notes = null, ?int $userId = null)
    {
        $quantity = abs($quantity);
        $previousStock = $this->stock;
        $newStock = $previousStock;

        if (in_array($type, ['purchase', 'adjustment_add', 'return'])) {
            $newStock += $quantity;
        } elseif (in_array($type, ['sale', 'adjustment_sub'])) {
            $newStock -= $quantity;
        }

        // Prevent negative stock? For now allow it but maybe warn? 
        // Let's allow it for flexibility in manual corrections.

        \DB::transaction(function () use ($quantity, $type, $notes, $userId, $previousStock, $newStock) {
            $this->update(['stock' => $newStock]);

            $this->inventoryMovements()->create([
                'user_id' => $userId ?? auth()->id(),
                'type' => $type,
                'quantity' => $quantity,
                'previous_stock' => $previousStock,
                'new_stock' => $newStock,
                'notes' => $notes,
            ]);
        });
    }

    public function getImageUrlAttribute()
    {
        if ($this->main_image_path) {
            if (str_starts_with($this->main_image_path, 'http')) {
                return $this->main_image_path;
            }
            return asset('storage/' . $this->main_image_path);
        }
        return null;
    }
}