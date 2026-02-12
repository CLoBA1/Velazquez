<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportHistory extends Model
{
    protected $fillable = [
        'user_id',
        'file_name',
        'total_rows',
        'processed_rows',
        'created_count',
        'updated_count',
        'skipped_count',
        'error_count',
        'summary_data',
    ];

    protected $casts = [
        'summary_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(ImportHistoryDetail::class);
    }
}
