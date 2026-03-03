<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo_path'];

    // Append computed logo_url so it appears in API JSON responses
    protected $appends = ['logo_url'];

    public function getLogoUrlAttribute()
    {
        return $this->logo_path
            ? asset('storage/' . $this->logo_path)
            : null;
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}