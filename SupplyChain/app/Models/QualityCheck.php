<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'stage', 'result', 'checked_by', 'checked_at', 'notes'
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function checker()
    {
        return $this->belongsTo(Workforce::class, 'checked_by');
    }
} 