<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilySequence extends Model
{
    protected $fillable = ['family_id', 'last_number'];

    protected $casts = [
        'last_number' => 'integer',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}