<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnReceived extends Model
{
    protected $fillable = [
        'order_id',
        'is_temp',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')
            ->select(
                'id', 'invoice_no', 'customer_name', 'customer_phone',
                'customer_address', 'courier_id', 'total', 'sub_total',
                'discount', 'paid', 'due', 'shipping_cost', 'created_at'
            )->with('get_courier');
    }
}
