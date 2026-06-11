<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Product;
use App\Models\ProductVariant;

class CartController extends Controller
{
    // ================= CART SESSION =================
    private function cart()
    {
        return Cart::session(Auth::id() ?? session()->getId());
    }

    // ================= ADD TO CART =================
    public function addCart(Request $request)
    {
        $quantity = $request->qty ?? 1;

        $product = Product::find($request->id);

        if (!$product) {
            return $request->ajax() ? response()->json(['success' => false, 'message' => 'Product not found']) : back()->with('error', 'Product not found');
        }

        // ================= VARIANT LOGIC =================
        $variant = null;
        $variantKey = null;
        $variantAttributes = [];
        $variantLabel = null;

        // dd($request->all());
        $attributeIds = $request->input('attributes');

        if (!empty($attributeIds) && is_array($attributeIds)) {

            sort($attributeIds); // normalize
            // find variants
            $attributeIds = array_map('intval', $attributeIds);
            $variant = ProductVariant::where('product_id', $product->id)
                ->with('variantItems.attribute', 'variantItems.attributeItem')
                ->get()
                ->filter(function ($variant) use ($attributeIds) {

                    $ids = $variant->variantItems
                        ->pluck('attribute_item_id')
                        ->map(fn($id) => (int) $id)
                        ->sort()
                        ->values()
                        ->toArray();

                    return $ids == $attributeIds; // use == not ===
                })
                ->first();

            // build variant key
            $variantKey = $variant ? implode('-', $attributeIds) : null;

            // build attributes label
            if ($variant) {
                foreach ($variant->variantItems as $item) {
                    if ($item->attribute && $item->attributeItem) {
                        $variantAttributes[$item->attribute->name] = $item->attributeItem->name;
                    }
                }

                $variantLabel = implode(' / ', array_values($variantAttributes));
            }
        }

        // ================= SKU / PRICE =================
        $sku = $variant ? $variant->variant_sku : $product->sku;
        $price = $variant ? $variant->variant_price : $product->sale_price;
        $name = $product->name;

        // ================= CART =================
        $cart = $this->cart();

        if ($cart->get($sku)) {

            $cart->update($sku, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ]);

        } else {

            $cart->add([
                'id' => $sku,
                'name' => $name,
                'price' => $price,
                'quantity' => $quantity,
                'attributes' => [
                    'variant_id' => $variant ? $variant->id : null,
                    'variant_key' => $variantKey,
                    'variant_attributes' => $variantAttributes,
                    'variant_label' => $variantLabel,
                ],
                'associatedModel' => $product,
            ]);
        }

        return $request->ajax()
            ? response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'count' => $cart->getContent()->count()
            ])
            : back()->with('success', 'Product added to cart successfully.');
    }

    // ================= UPDATE QTY =================
    public function updateQty(Request $request)
    {
        $cart = $this->cart();

        $cart->update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity,
            ],
        ]);

        return response()->json(['success' => 'Quantity updated']);
    }

    // ================= PLUS =================
    public function cartItemPlus(Request $request)
    {
        $cart = $this->cart();

        $cart->update($request->id, [
            'quantity' => 1,
        ]);

        return response()->json(['success' => 'Quantity increased']);
    }

    // ================= MINUS =================
    public function cartItemMinus(Request $request)
    {
        $cart = $this->cart();

        $cart->update($request->id, [
            'quantity' => -1,
        ]);

        return response()->json(['success' => 'Quantity decreased']);
    }

    // ================= REMOVE =================
    public function cartItemRemove(Request $request)
    {
        $cart = $this->cart();

        $cart->remove($request->id);

        return response()->json([
            'success' => true,
            'total' => $cart->getTotal(),
            'count' => $cart->getContent()->count(),
        ]);
    }

    // ================= CLEAR CART =================
    public function cartClear()
    {
        $cart = $this->cart();

        $cart->clear();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
        ]);
    }

    // ================= VIEW CART DEBUG =================
    public function ddCart()
    {
        $cart = $this->cart();

        $items = $cart->getContent()->sortBy('id');

        return dd($items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'attributes' => $item->attributes,
            ];
        }));
    }

    public function clearCart()
    {
        $cart = $this->cart();
        Cart::session($cart)->clear();

        session()->flush();        // remove all session data
        session()->invalidate();   // destroy session
        session()->regenerate();   // new session

        return 'Cart + Session Reset Done';
    }
}
