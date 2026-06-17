<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    protected $fillable = ['product_id', 'sku', 'date', 'quantity', 'amount', 'note'];

    protected $casts = [
        'date' => 'datetime', // <-- this is the key
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function get_product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
