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

    // Eager loading helper for frontend views
    public function sale_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // 🔗 Belongs to customer (customers table)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // 🔗 Belongs to store
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Store helper
    public function get_store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    // 🔗 Belongs to courier
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    // Courier helper
    public function get_courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    // 🔗 Assigned to user
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
