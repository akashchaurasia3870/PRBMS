<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseTracker extends Model
{
    use SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = [
        'type',
        'amount',
        'description',
        'expense_date',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted'
    ];

    protected $dates = ['expense_date', 'created_at', 'updated_at', 'deleted_at'];
}
