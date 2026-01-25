<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $fillable = ['name', 'slug', 'code'];

    public function categories()
    {
        return $this->hasMany(Category::class);
    }
}