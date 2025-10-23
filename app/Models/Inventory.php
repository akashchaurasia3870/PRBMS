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
        'deleted_by', 'created_by', 'min_stock_level', 'max_stock_level',
        'barcode', 'location', 'supplier', 'expiry_date'
    ];

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'item_price' => 'decimal:2',
        'item_qty' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    
    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
    
    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }
    
    // Accessors
    public function getTotalValueAttribute()
    {
        return $this->item_price * $this->item_qty;
    }
    
    public function getStockStatusAttribute()
    {
        if ($this->item_qty <= ($this->min_stock_level ?? 5)) {
            return 'low';
        } elseif ($this->item_qty >= ($this->max_stock_level ?? 100)) {
            return 'high';
        }
        return 'normal';
    }
    
    public function getStockStatusColorAttribute()
    {
        return match($this->stock_status) {
            'low' => 'red',
            'high' => 'blue',
            default => 'green'
        };
    }
    
    // Scopes
    public function scopeLowStock($query, $threshold = null)
    {
        return $query->whereRaw('item_qty <= COALESCE(min_stock_level, ?)', [$threshold ?? 10]);
    }
    
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
    
    public function scopeByPriceRange($query, $min, $max)
    {
        return $query->whereBetween('item_price', [$min, $max]);
    }
    
    public function scopeByStockRange($query, $min, $max)
    {
        return $query->whereBetween('item_qty', [$min, $max]);
    }
    
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('item_name', 'LIKE', "%{$term}%")
              ->orWhere('item_code', 'LIKE', "%{$term}%")
              ->orWhere('item_description', 'LIKE', "%{$term}%")
              ->orWhere('barcode', 'LIKE', "%{$term}%")
              ->orWhere('location', 'LIKE', "%{$term}%")
              ->orWhere('supplier', 'LIKE', "%{$term}%");
        });
    }
    
    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }
    
    public function scopeBySupplier($query, $supplier)
    {
        return $query->where('supplier', $supplier);
    }
    
    public function scopeExpiringWithin($query, $days = 30)
    {
        return $query->whereNotNull('expiry_date')
                    ->where('expiry_date', '<=', now()->addDays($days));
    }
}