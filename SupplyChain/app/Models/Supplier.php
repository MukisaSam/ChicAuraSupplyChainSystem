<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_address',
        'phone',
        'license_document',
        'materials_supplied',
    ];

    protected $casts = [
        'materials_supplied' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class);
    }

    public function suppliedItems()
    {
        return $this->hasMany(SuppliedItem::class);
    }

    public function priceNegotiations()
    {
        return $this->hasMany(PriceNegotiation::class);
    }
} 