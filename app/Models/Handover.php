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

    public function orderFilter()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->select('id', 'invoice_no', 'courier_id', 'status', 'created_at');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }
}
