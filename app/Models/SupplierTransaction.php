<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTransaction extends Model
{
    protected $fillable = [
        'supplier_id',
        'type',
        'amount',
        'reference_type',
        'reference_id',
        'note',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
