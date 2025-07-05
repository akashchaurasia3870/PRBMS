<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documents extends Model
{
    use HasFactory;
    protected $table = 'documents';
    protected $fillable = [
        'user_id',
        'doc_type',
        'doc_desc',
        'doc_url',
    ];
}
