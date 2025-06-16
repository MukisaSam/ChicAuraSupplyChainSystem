<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuppliedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'item_id',
        'price',
        'delivered_quantity',
        'delivery_date',
        'quality_rating',
        'status',
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
} 