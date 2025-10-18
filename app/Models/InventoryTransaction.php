<?php
// app/Models/InventoryTransaction.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = ['inventory_id', 'type', 'quantity', 'user_id'];

    public function auditLogs()
    {
        return $this->morphMany(\App\Models\AuditLog::class, 'auditable');
    }
}
