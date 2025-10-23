<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = 'users_leave';
    
    protected $fillable = [
        'user_id', 'leave_type', 'reason', 'description', 'status',
        'start_date', 'end_date', 'deleted', 'deleted_by', 'created_by',
        'rejection_reason', 'approved_by', 'rejected_by', 'days_count'
    ];

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_count' => 'integer',
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
    
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
    
    // Accessors
    public function getDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            return $this->start_date->diffInDays($this->end_date) + 1;
        }
        return $this->days_count ?? 0;
    }
    
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'approved' => 'green',
            'rejected' => 'red',
            'pending' => 'yellow',
            default => 'gray'
        };
    }
    
    public function getLeaveTypeColorAttribute()
    {
        return match($this->leave_type) {
            'sick' => 'red',
            'vacation' => 'blue',
            'personal' => 'purple',
            'emergency' => 'orange',
            default => 'gray'
        };
    }
    
    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
    public function scopeByLeaveType($query, $type)
    {
        return $query->where('leave_type', $type);
    }
    
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
    
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('reason', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%")
              ->orWhere('rejection_reason', 'LIKE', "%{$term}%")
              ->orWhereHas('user', function($userQuery) use ($term) {
                  $userQuery->where('name', 'LIKE', "%{$term}%")
                           ->orWhere('email', 'LIKE', "%{$term}%");
              });
        });
    }
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now())
                    ->where('status', 'approved');
    }
    
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->where('status', 'approved');
    }
}
