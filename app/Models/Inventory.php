<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'item_code', 'item_name', 'item_description',
        'item_img_path', 'item_qty','item_price','category_id',
        'deleted_by', 'created_by'
    ];

    protected $dates = ['deleted_at'];
    
    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
