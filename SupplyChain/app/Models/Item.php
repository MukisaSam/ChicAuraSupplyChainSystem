<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Warehouse;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'material',
        'base_price',
        'size_range',
        'color_options',
        'stock_quantity',
        'image_url',
        'is_active',
        'type',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function supplyRequests()
    {
        return $this->hasMany(SupplyRequest::class);
    }

    public function suppliedItems()
    {
        return $this->hasMany(SuppliedItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
