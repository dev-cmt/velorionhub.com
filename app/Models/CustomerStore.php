<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStore extends Model
{
    protected $fillable = [
        'customer_id',
        'store_id',
    ];
}
