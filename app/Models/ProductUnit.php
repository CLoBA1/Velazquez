<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ProductUnit extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'product_id',
        'unit_id',
        'conversion_factor',
        'cost_price',
        'taxes_percent',
        'sale_price',
        'public_price',
        'mid_wholesale_price',
        'wholesale_price',
        'barcode',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'conversion_factor',
                'cost_price',
                'sale_price',
                'public_price',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
