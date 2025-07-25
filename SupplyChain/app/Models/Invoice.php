<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'amount',
        'status',
        'due_date',
        'pdf_path',
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
