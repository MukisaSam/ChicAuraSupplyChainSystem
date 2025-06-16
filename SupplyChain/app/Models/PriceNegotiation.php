<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceNegotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'supply_request_id',
        'initial_price',
        'counter_price',
        'status',
        'notes',
        'negotiation_date',
    ];

    protected $casts = [
        'initial_price' => 'decimal:2',
        'counter_price' => 'decimal:2',
        'negotiation_date' => 'datetime',
    ];

    public function supplyRequest()
    {
        return $this->belongsTo(SupplyRequest::class);
    }
} 