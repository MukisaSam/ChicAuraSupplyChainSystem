<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DowntimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'start_time', 'end_time', 'reason', 'notes'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }
} 