<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    protected $fillable = [
        'store_id',
        'customer_name',
        'customer_address',
        'customer_phone',
        'abandoned_item',
        'shipping_cost',
        'subtotal',
        'total',
        'note',
    ];

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }
}


