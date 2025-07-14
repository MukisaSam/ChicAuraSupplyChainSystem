<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'capacity',
        'manufacturer_id',
    ];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function workforce()
    {
        return $this->belongsToMany(\App\Models\Workforce::class, 'warehouse_workforce');
    }
} 