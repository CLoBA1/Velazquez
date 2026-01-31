<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'internal_code',
        'brand',
        'model',
        'price_per_hour',
        'price_per_day',
        'status',
        'description',
        'main_image_path',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'price_per_day' => 'decimal:2',
    ];

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
