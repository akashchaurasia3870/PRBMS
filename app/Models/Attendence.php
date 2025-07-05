<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Attendence extends Model
{
    use HasFactory;
    protected $table = 'attendence';
    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'deleted',
        'deleted_by',
        'deleted_at'
    ];
}
