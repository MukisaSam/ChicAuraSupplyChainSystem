<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workforce extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'email',
        'contact_info',
        'address',
        'job_role',
        'status',
        'hire_date',
        'salary',
        'manufacturer_id',
    ];

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function warehouses()
    {
        return $this->belongsToMany(\App\Models\Warehouse::class, 'warehouse_workforce');
    }
} 