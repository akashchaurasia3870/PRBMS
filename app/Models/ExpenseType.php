<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseType extends Model
{
    use SoftDeletes;

    protected $table = 'expense_type';

    protected $fillable = [
        'type',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted'
    ];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
}
