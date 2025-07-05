<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;
    protected $table = 'users_leave';
    protected $fillable = [
        'user_id',
        'leave_type',
        'reason',
        'description',
        'status',
        'start_date',
        'end_date',
        'deleted',
        'deleted_by',
        'deleted_at'
    ];
}
