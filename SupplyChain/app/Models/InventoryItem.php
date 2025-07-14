<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'name',
        'sku',
        'quantity',
        'min_stock',
        'max_stock',
        'batch_number',
        'expiry_date',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
