<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrderAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'workforce_id', 'role', 'assigned_at'
    ];

    protected $table = 'work_order_assignments';
    protected $primaryKey = 'id';

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function workforce()
    {
        return $this->belongsTo(Workforce::class);
    }
} 