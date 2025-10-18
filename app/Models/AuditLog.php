<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // Only using created_at timestamp explicitly

    protected $fillable = [
        'auditable_id',
        'auditable_type',
        'user_id',
        'action',
        'changes',
        'remarks',
        'created_at',
    ];

    protected $casts = [
        'changes' => 'array',
        'created_at' => 'datetime',
    ];

    // Polymorphic relation to auditable models
    public function auditable()
    {
        return $this->morphTo();
    }

    // Relation to the user who did the action
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
