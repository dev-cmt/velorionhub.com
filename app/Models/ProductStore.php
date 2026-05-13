<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductStore extends Pivot
{
    protected $fillable = [
        'product_id',
        'store_id',
        'quantity',
        'alert_quantity',
    ];

    /**
     * Relationship to the Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship to the Store
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}