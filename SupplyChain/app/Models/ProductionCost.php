<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'material_cost', 'labor_cost', 'overhead_cost', 'total_cost'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 