<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    protected $fillable = [
        'order_id',
        'is_temp',
    ];

    // Filtered eager-load for list views
    public function orderFilter()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')
            ->select('id', 'invoice_no', 'courier_id', 'status', 'created_at');
    }

    // Full order relationship
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
