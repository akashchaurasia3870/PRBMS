<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'code', 'deleted', 'deleted_by', 'created_by', 'updated_by', 'color', 'icon', 'is_active'
    ];

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'deleted' => 'boolean',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    // Relationships
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
    
    // Accessors
    public function getTotalItemsAttribute()
    {
        return $this->inventories()->count();
    }
    
    public function getTotalValueAttribute()
    {
        return $this->inventories()->sum(\DB::raw('item_price * item_qty'));
    }
    
    public function getLowStockItemsAttribute()
    {
        return $this->inventories()->lowStock()->count();
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('deleted', 0)->where('is_active', true);
    }
    
    public function scopeWithStats($query)
    {
        return $query->withCount('inventories')
                    ->addSelect([
                        'total_value' => \DB::raw('(SELECT SUM(item_price * item_qty) FROM inventories WHERE category_id = categories.id)'),
                        'low_stock_count' => \DB::raw('(SELECT COUNT(*) FROM inventories WHERE category_id = categories.id AND item_qty <= COALESCE(min_stock_level, 10))')
                    ]);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('code', 'LIKE', "%{$term}%")
              ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }
}