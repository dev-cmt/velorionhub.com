<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function transactions()
    {
        return $this->hasMany(SupplierTransaction::class)
                    ->latest();
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor (Dynamic Balance)
    |--------------------------------------------------------------------------
    */

    public function getBalanceAttribute()
    {
        $debit = $this->transactions()->where('type', 'debit')->sum('amount');
        $credit = $this->transactions()->where('type', 'credit')->sum('amount');

        return $debit - $credit; // payable
    }
}
