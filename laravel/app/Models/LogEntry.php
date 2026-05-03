<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogEntry extends Model
{
    protected $fillable = ['server_id', 'level', 'category', 'message', 'raw_log', 'source', 'ip_address', 'status_code', 'severity', 'occurred_at'];
    protected $casts = ['occurred_at' => 'datetime'];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }

    public function aiAnalysis()
    {
        return $this->hasOne(AiAnalysis::class);
    }
}
