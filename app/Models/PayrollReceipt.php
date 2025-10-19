<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'basic_salary',
        'hra',
        'da',
        'other_allowance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
