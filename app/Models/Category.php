<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description', 'code'
    ];

    // Relationships
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }


    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
