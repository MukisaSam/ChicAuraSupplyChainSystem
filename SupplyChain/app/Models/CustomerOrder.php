<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'billing_address',
        'notes',
        'estimated_delivery',
        'actual_delivery',
        'tracking_number',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'estimated_delivery' => 'date',
        'actual_delivery' => 'date',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerOrderItems()
    {
        return $this->hasMany(\App\Models\CustomerOrderItem::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'secondary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'clock',
            'confirmed' => 'check-circle',
            'processing' => 'cog',
            'shipped' => 'truck',
            'delivered' => 'check-circle',
            'cancelled' => 'x-circle',
            default => 'circle'
        };
    }

    public function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'shipped' => 'Shipped',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled'
        ];
    }

    public function getPaymentStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
    }

    public function getPaymentMethodOptions()
    {
        return [
            'credit_card' => 'Credit Card',
            'debit_card' => 'Debit Card',
            'paypal' => 'PayPal',
            'bank_transfer' => 'Bank Transfer',
            'cash_on_delivery' => 'Cash on Delivery',
            'mobile_money' => 'Mobile Money'
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'CUS-' . date('Y') . '-' . str_pad(
                    CustomerOrder::whereYear('created_at', date('Y'))->count() + 1, 
                    6, 
                    '0', 
                    STR_PAD_LEFT
                );
            }
        });
    }
}