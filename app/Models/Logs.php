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
}
