<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'order_number',
        'status',
        'order_date',
        'total_amount',
        'payment_method',
        'delivery_address',
        'notes',
        'estimated_delivery',
        'actual_delivery',
        'manufacturer_id',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'estimated_delivery' => 'datetime',
        'actual_delivery' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-500',
            'confirmed' => 'bg-blue-500',
            'in_production' => 'bg-purple-500',
            'shipped' => 'bg-indigo-500',
            'delivered' => 'bg-green-500',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-500',
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'fa-clock',
            'confirmed' => 'fa-check-circle',
            'in_production' => 'fa-cogs',
            'shipped' => 'fa-shipping-fast',
            'delivered' => 'fa-check',
            'cancelled' => 'fa-times',
            default => 'fa-question',
        };
    }
} 