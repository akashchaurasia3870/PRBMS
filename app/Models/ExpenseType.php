<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseType extends Model
{
    use HasFactory, SoftDeletes;

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

    // Relationships
    public function expenses()
    {
        return $this->hasMany(ExpenseTracker::class, 'expense_type_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function hasExpenses()
    {
        // Check if any expenses use this expense type by type name
        return \App\Models\ExpenseTracker::where('type', $this->type)
            ->where('deleted', 0)
            ->exists();
    }

    public function getExpenseIdAttribute()
    {
        return 'EXP' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }
}