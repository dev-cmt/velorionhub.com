<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'unit_type',
        'status',
    ];

    // unit types (optional helper)
    const TYPE_WEIGHT   = 'weight';
    const TYPE_VOLUME   = 'volume';
    const TYPE_QUANTITY = 'quantity';

    /**
     * Products using this unit
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
