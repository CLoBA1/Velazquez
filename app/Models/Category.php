<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['family_id', 'name', 'slug'];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}