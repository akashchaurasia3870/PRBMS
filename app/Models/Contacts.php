<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contacts extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $fillable = [
        'country',
        'state',
        'city',
        'area',
        'locality',
        'landmark',
        'street',
        'house_no',
        'contact_no',
        'emergency_contact_no',
        'pincode',
        'user_id',
        'deleted',
        'deleted_by',
        'deleted_at',
    ];

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
