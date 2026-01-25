<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'name',
        'rfc',
        'email',
        'phone',
        'address',
        'tax_system',
        'credit_limit',
        'credit_used',
    ];

    protected $appends = ['available_credit'];

    public function payments()
    {
        return $this->hasMany(ClientPayment::class);
    }

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'credit_used' => 'decimal:2',
    ];

    public function getAvailableCreditAttribute()
    {
        return $this->credit_limit - $this->credit_used;
    }
}
