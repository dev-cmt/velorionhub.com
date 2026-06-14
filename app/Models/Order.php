<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_no',
        'source',
        'customer_name',
        'customer_phone',
        'customer_address',
        'customer_activity',
        'sub_total',
        'shipping_cost',
        'discount',
        'total',
        'paid',
        'due',
        'payment_method',
        'payment_status',
        'status',
        'notes',
        'remarks',

        'courier_id',
        'consignment_id',
        'tracking_code',
        'tracking_url',

        'store_id',
        'customer_id',
        'assigned_to',
        'is_requisition',
    ];

    // 🔗 Order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // 🔗 Belongs to customer (users table)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // 🔗 Belongs to store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // 🔗 Belongs to courier
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // 🔗 Assigned to user
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
