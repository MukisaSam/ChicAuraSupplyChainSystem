<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wholesaler extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_address',
        'phone',
        'license_document',
        'business_type',
        'document_path',
        'preferred_categories',
        'monthly_order_volume',
    ];

    protected $casts = [
        'preferred_categories' => 'array',
        'monthly_order_volume' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
} 