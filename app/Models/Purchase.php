<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'store_id',
        'memo_number',
        'purchase_date',
        'status',
        'sub_total',
        'discount',
        'tax',
        'grand_total',
        'paid_amount',
        'due_amount',
        'remarks',
    ];

    protected $casts = [
        'purchase_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function purchase_items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function payments()
    {
        return $this->hasMany(PurchasePayment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }

    public function getCalculatedDueAttribute()
    {
        return $this->grand_total - $this->total_paid;
    }

    public function getDateAttribute()
    {
        return $this->purchase_date;
    }

    public function getTotalAttribute()
    {
        return $this->grand_total;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function updateAmounts()
    {
        $subTotal = $this->items()->sum('total');

        $grandTotal = $subTotal - $this->discount + $this->tax;
        $paid = $this->payments()->sum('amount');
        $due = $grandTotal - $paid;

        $this->update([
            'sub_total'    => $subTotal,
            'grand_total'  => $grandTotal,
            'paid_amount'  => $paid,
            'due_amount'   => $due,
        ]);
    }

    public function updateStatus()
    {
        $totalOrdered = $this->items()->sum('ordered_qty');
        $totalReceived = $this->items()->sum('received_qty');

        if ($totalReceived == 0) {
            $status = 0; // Ordered/Pending
        } elseif ($totalReceived < $totalOrdered) {
            $status = 2; // Partial Receive
        } else {
            $status = 1; // Completed/Received
        }

        $this->update(['status' => $status]);
    }
}
