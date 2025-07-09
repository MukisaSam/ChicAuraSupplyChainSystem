<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }

    public function components()
    {
        return $this->hasMany(BillOfMaterialComponent::class, 'bom_id');
    }
} 