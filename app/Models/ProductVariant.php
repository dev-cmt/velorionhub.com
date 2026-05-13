<?php
// app/Models/ProductVariant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'variant_sku', 'variant_price', 'purchase_cost', 'variant_stock', 'attribute_item_ids'
    ];

    protected $casts = [
        'variant_price' => 'decimal:2',
        'purchase_cost' => 'decimal:2',
        'variant_stock' => 'integer',
        'attribute_item_ids' => 'array',
    ];

    // Relationships
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variantItems(): HasMany
    {
        return $this->hasMany(ProductVariantItem::class);
    }

    public function attributeItems()
    {
        return $this->belongsToMany(AttributeItem::class, 'product_variant_items')
                    ->withPivot('image')
                    ->withTimestamps();
    }

    public function scopeInStock($query)
    {
        return $query->where('variant_stock', '>', 0);
    }

    public function getFinalPriceAttribute()
    {
        // Check if there's a variant-specific discount
        $discount = $this->product->currentDiscount;

        if ($discount) {
            if ($discount->discount_type === 'percentage') {
                return $this->variant_price - ($this->variant_price * $discount->amount / 100);
            } else {
                return max(0, $this->variant_price - $discount->amount);
            }
        }

        return $this->variant_price;
    }
}
