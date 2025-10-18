<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_name',
        'role_desc',
        'role_lvl',
    ];

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
