<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryStructure extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'basic_salary', 'hra', 'da', 'other_allowance',
        'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hra' => 'decimal:2',
        'da' => 'decimal:2',
        'other_allowance' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
    
    public function payrollReceipts()
    {
        return $this->hasMany(PayrollReceipt::class, 'user_id', 'user_id');
    }
    
    // Accessors
    public function getGrossSalaryAttribute()
    {
        return $this->basic_salary + $this->hra + $this->da + $this->other_allowance;
    }
    
    public function getHraPercentageAttribute()
    {
        return $this->basic_salary > 0 ? round(($this->hra / $this->basic_salary) * 100, 1) : 0;
    }
    
    public function getDaPercentageAttribute()
    {
        return $this->basic_salary > 0 ? round(($this->da / $this->basic_salary) * 100, 1) : 0;
    }
    
    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->whereHas('user', function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }
    
    public function scopeBySalaryRange($query, $min, $max)
    {
        return $query->whereBetween('basic_salary', [$min, $max]);
    }
}
