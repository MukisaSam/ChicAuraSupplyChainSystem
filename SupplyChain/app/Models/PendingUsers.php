<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'visitDate',
        'error_details',
        'business_address',
        'phone',
        'license_document',
        'document_path',
        'business_type',
        'monthly_order_volume',
        'production_capacity',
        'preferred_categories',
        'specialization',
        'materials_supplied',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'specialization' => 'array',
        'production_capacity' => 'integer',
        'preferred_categories' => 'array',
        'monthly_order_volume' => 'integer',
        'materials_supplied' => 'array',
        'visitDate' => 'datetime',
    ];

    
} 