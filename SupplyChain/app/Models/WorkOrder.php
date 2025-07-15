<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'quantity', 'status', 'scheduled_start', 'scheduled_end', 'actual_start', 'actual_end', 'notes'
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Item::class, 'product_id');
    }

    public function productionSchedule()
    {
        return $this->hasOne(ProductionSchedule::class);
    }

    public function qualityChecks()
    {
        return $this->hasMany(QualityCheck::class);
    }

    public function assignments()
    {
        return $this->hasMany(WorkOrderAssignment::class);
    }

    public function downtimeLogs()
    {
        return $this->hasMany(DowntimeLog::class);
    }

    public function productionCost()
    {
        return $this->hasOne(ProductionCost::class);
    }
} 