<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollReceipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'month', 'year', 'total_working_days', 'present_days', 'leave_days',
        'total_salary', 'net_salary', 'status', 'generated_at', 'paid_at',
        'created_by', 'updated_by', 'deleted_by'
    ];

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'total_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'total_working_days' => 'integer',
        'present_days' => 'integer',
        'leave_days' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'generated_at' => 'datetime',
        'paid_at' => 'datetime',
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
    
    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'paid' => 'green',
            'generated' => 'blue',
            'pending' => 'yellow',
            default => 'gray'
        };
    }
    
    public function getMonthNameAttribute()
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }
    
    public function getAttendancePercentageAttribute()
    {
        return $this->total_working_days > 0 
            ? round(($this->present_days / $this->total_working_days) * 100, 1)
            : 0;
    }
    
    public function getPerDaySalaryAttribute()
    {
        return $this->total_working_days > 0 
            ? round($this->total_salary / $this->total_working_days, 2)
            : 0;
    }
    
    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }
    
    public function scopeByYear($query, $year)
    {
        return $query->where('year', $year);
    }
    
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
    
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'generated');
    }
    
    public function scopeCurrentMonth($query)
    {
        return $query->where('month', now()->month)
                    ->where('year', now()->year);
    }
}
