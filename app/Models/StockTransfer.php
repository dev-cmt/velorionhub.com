<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'product_id',
        'from_store_id',
        'to_store_id',
        'quantity',
        'note',
        'status',
        'requested_by',
        'approved_by',
        'approved_at'
    ];

    // Helper to generate Reference Number
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->reference_no = 'TRF-' . strtoupper(uniqid());
            $model->requested_by = Auth::id();
        });
    }

    /* --- Relationships --- */

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fromStore()
    {
        return $this->belongsTo(Store::class, 'from_store_id');
    }

    public function toStore()
    {
        return $this->belongsTo(Store::class, 'to_store_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

}