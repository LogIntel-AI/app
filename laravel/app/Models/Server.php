<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = ['user_id', 'name', 'ip_address', 'environment', 'os_type', 'status', 'api_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logEntries()
    {
        return $this->hasMany(LogEntry::class);
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
