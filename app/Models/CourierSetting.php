<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierSetting extends Model
{
    protected $fillable = [
        'store_id',
        'courier_id',
        'store_code',
        'phone',
        'email',
        'password',
        'api_key',
        'secret_key',
        'access_token',
        'refresh_token',
        'client_id',
        'client_secret',
        'client_context',
        'status',
    ];

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
