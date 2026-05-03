<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiAnalysis extends Model
{
    protected $fillable = ['log_entry_id', 'category', 'severity', 'summary', 'suggestion', 'model_used'];

    public function logEntry()
    {
        return $this->belongsTo(LogEntry::class);
    }
}
