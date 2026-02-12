<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportHistoryDetail extends Model
{
    protected $fillable = [
        'import_history_id',
        'row_number',
        'status',
        'message',
        'row_data',
    ];

    protected $casts = [
        'row_data' => 'array',
    ];

    public function history()
    {
        return $this->belongsTo(ImportHistory::class, 'import_history_id');
    }
}
