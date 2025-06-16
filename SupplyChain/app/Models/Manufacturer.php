<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_address',
        'phone',
        'license_document',
        'production_capacity',
        'specialization',
    ];

    protected $casts = [
        'specialization' => 'array',
        'production_capacity' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 