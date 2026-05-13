<?php
// ServiceTicketProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceTicketProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_ticket_id',
        'product_id',
        'quantity',
        'notes',
    ];

    public function serviceTicket()
    {
        return $this->belongsTo(ServiceTicket::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}