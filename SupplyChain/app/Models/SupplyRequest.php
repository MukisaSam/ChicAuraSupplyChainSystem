<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'item_id',
        'quantity',
        'due_date',
        'status',
        'payment_type',
        'delivery_method',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function priceNegotiation()
    {
        return $this->hasOne(PriceNegotiation::class);
    }
} 