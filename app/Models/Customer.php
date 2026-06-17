<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'address', 'balance', 'status'
    ];

    public function get_store()
    {
        return $this->belongsToMany(Store::class, 'customer_stores', 'customer_id', 'store_id');
    }
}
