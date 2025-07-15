<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_order_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_price',
        'notes',
        'size',
        'color',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function customerOrder()
    {
        return $this->belongsTo(CustomerOrder::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($orderItem) {
            $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
        });
    }
}