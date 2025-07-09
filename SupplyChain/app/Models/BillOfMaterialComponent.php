<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillOfMaterialComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id', 'raw_item_id', 'quantity'
    ];

    public function billOfMaterial()
    {
        return $this->belongsTo(BillOfMaterial::class, 'bom_id');
    }

    public function rawItem()
    {
        return $this->belongsTo(Item::class, 'raw_item_id');
    }
} 