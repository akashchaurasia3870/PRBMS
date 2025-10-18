<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $fillable = [
        'user_id',
        'url',
        'full_url',
        'ip_address',
        'mac_address',
        'request_data',
    ];

    // public function auditLogs()
    // {
    //     return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    // }
}
