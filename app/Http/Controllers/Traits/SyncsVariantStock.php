<?php

namespace App\Http\Controllers\Traits;

use App\Models\Product;

/**
 * Reusable trait to keep product.total_stock in sync with
 * the sum of all variant_stock values whenever a variant's
 * stock is incremented or decremented.
 */
trait SyncsVariantStock
{
    /**
     * Set product.total_stock = SUM(variant_stock) for all variants.
     * Call this after any variant_stock increment / decrement.
     */
    protected function syncVariantTotalStock(Product $product): void
    {
        $total = $product->variants()->sum('variant_stock');
        $product->update(['total_stock' => $total]);
    }
}
