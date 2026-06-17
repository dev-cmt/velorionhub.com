<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'sku',
        'ordered_qty',
        'received_qty',
        'purchase_cost',
        'sale_price',
        'total',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function get_product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getQuantityAttribute()
    {
        return $this->ordered_qty;
    }

    public function getPurchaseQtyAttribute()
    {
        return $this->ordered_qty;
    }

    public function getRecivedQtyAttribute()
    {
        return $this->received_qty;
    }

    public function getSellPriceAttribute()
    {
        return $this->sale_price;
    }

    /*
    |--------------------------------------------------------------------------
    | Boot Method (Auto total calculation)
    |--------------------------------------------------------------------------
    */

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->total = $item->ordered_qty * $item->purchase_cost;
        });

        static::saved(function ($item) {
            $item->purchase->updateAmounts();
            $item->purchase->updateStatus();
        });

        static::deleted(function ($item) {
            $item->purchase->updateAmounts();
            $item->purchase->updateStatus();
        });
    }
}
