<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    private function cart()
    {
        $id = Auth::id() ?? session()->getId() ?? 'guest';
        return Cart::session($id);
    }

    public function addCart(Request $request)
    {
        $quantity = $request->qty ?? 1;
        $product = Product::find($request->product_id);
        if (! $product) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Product not found'])
                : back()->with('error', 'Product not found');
        }

        // ---------- Variant logic ----------
        $variantKey = null;

        if ($request->attribute_item_id && is_array($request->attribute_item_id)) {
            $variantKey = strtolower(implode('-', $request->attribute_item_id));
        }

        $variant = null;

        if ($variantKey) {
            $variant = ProductVariant::where('product_id', $product->id)->where('variant', $variantKey)->first();
        }

        $sku   = $variant->sku ?? $product->sku;
        $name  = $variant->product->name ?? $product->name;
        $price = $variant ? $variant->sale_price : $product->sale_price;
        $variantAttributes = [];
        $variantLabel = null;

        if ($variant) {
            foreach ($variant->variantItems as $variantItem) {
                if ($variantItem->attribute && $variantItem->attributeItem) {
                    $variantAttributes[$variantItem->attribute->name] = $variantItem->attributeItem->name;
                }
            }

            if (!empty($variantAttributes)) {
                $variantLabel = implode(' / ', array_values($variantAttributes));
            }
        }

        // ---------- Cart logic ----------
        if (Cart::get($sku)) {
            Cart::update($sku, [
                'quantity' => [
                    'relative' => false,
                    'value' => $quantity,
                ],
            ]);
        } else {
            Cart::add([
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

        // ---------- AJAX response ----------
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart',
                'count'   => Cart::getContent()->count()
            ]);
        } else {
            return back()->with('success', 'Product added to cart successfully.');
        }
    }
    public function cartItemPlus(Request $request)
    {
        // Check cart item exists
        $item = Cart::get($request->id);
        if (! $item) {
            return response()->json(['error' => 'Cart item not found']);
        }

        if (Cart::getContent()->count() > 0) {
            Cart::update($request->id, [
                'quantity' => 1,
            ]);

            return response()->json(['success' => 'Cart quantity updated successfully!']);
        } else {
            return response()->json(['error' => 'error']);
        }
    }
    public function cartItemMinus(Request $request)
    {
        $item = Cart::get($request->id);
        if (! $item) {
            return response()->json(['error' => 'Cart item not found']);
        }

        if (Cart::getContent()->count() > 0) {
            Cart::update($request->id, [
                'quantity' => -1,
            ]);

            return response()->json(['success' => 'Cart quantity updated successfully!']);
        } else {
            return response()->json(['error' => 'error']);
        }
    }
    public function cartItemDelete(Request $request)
    {
        Cart::remove($request->id);
        $total = Cart::getTotal();
        $total_cart = count(Cart::getContent());
        if ($request->ajax()) {
            return response()->json([
                'success' => 'Cart Item Deleted Successfully.',
                'total' => $total,
                'total_cart' => $total_cart,
            ]);
        } else {
            return back()->with('success', 'Cart item delete successfully.');
        }
    }
    public function cartClear()
    {
        Cart::clear();

        return response()->json([
            'success' => 200,
            'message' => 'Cart cleared successfully',
        ]);
    }




    /* ADD TO CART */
    public function add(Request $request)
    {
        try {
            $productId = $request->id;
            $qty = $request->qty ?? $request->count ?? 1;

            if (!$productId) {
                return response()->json(['success' => false, 'message' => 'Product ID is required'], 400);
            }

            $product = Product::with('variants.variantItems.attribute', 'variants.variantItems.attributeItem')->find($productId);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            $name  = $request->name ?? $product->name;
            $price = $request->price ?? $product->sale_price;
            $image = $request->image ?? ($product->main_image ? asset($product->main_image) : asset('images/no-image.jpg'));
            $url   = $request->url ?? route('product.show', $product->slug);
            $variantId = null;
            $variantAttributes = [];
            $variantLabel = null;

            // Handle Variants: accept several shapes from the client
            $selectedAttrIds = [];
            if ($request->has('attributes')) {
                $attrs = $request->attributes;
                if (is_array($attrs)) {
                    $selectedAttrIds = array_values($attrs);
                } elseif (is_string($attrs)) {
                    // Try JSON or CSV
                    $decoded = json_decode($attrs, true);
                    if (is_array($decoded)) {
                        $selectedAttrIds = array_values($decoded);
                    } else {
                        $selectedAttrIds = array_filter(array_map('trim', explode(',', $attrs)));
                    }
                }
            } elseif ($request->has('attribute_item_id')) {
                // legacy form field name
                $a = $request->attribute_item_id;
                $selectedAttrIds = is_array($a) ? $a : [$a];
            }

            // Normalize to integers and sort for comparison
            $selectedAttrIds = array_map('intval', $selectedAttrIds);
            sort($selectedAttrIds);
            $selectedVariantKey = implode('-', $selectedAttrIds);

            // Log incoming attributes for debugging (no functional change)
            try {
                Log::info('cart.add request', [
                    'product_id' => $product->id ?? $productId,
                    'raw_attributes_input' => $request->input('attributes'),
                    'normalized_selected_attr_ids' => $selectedAttrIds,
                    'all_payload' => $request->all()
                ]);
            } catch (\Exception $e) {
                // swallow logging errors to avoid breaking add flow
            }

            if (!empty($selectedAttrIds)) {
                foreach ($product->variants as $variant) {
                    $variantAttrIds = $variant->variantItems->pluck('attribute_item_id')->toArray();
                    $variantAttrIds = array_map('intval', $variantAttrIds);
                    sort($variantAttrIds);
                    if (implode('-', $variantAttrIds) === $selectedVariantKey) {
                        $variantId = $variant->id;
                        $price = $variant->sale_price;
                        $productId = 'v' . $variant->id;

                        foreach ($variant->variantItems as $variantItem) {
                            if ($variantItem->attribute && $variantItem->attributeItem) {
                                $variantAttributes[$variantItem->attribute->name] = $variantItem->attributeItem->name;
                            }
                        }

                        if (!empty($variantAttributes)) {
                            $variantLabel = implode(' / ', array_values($variantAttributes));
                        }
                        break;
                    }
                }
            }

            $this->cart()->add([
                'id' => $productId,
                'name' => $name,
                'quantity' => $qty,
                'price' => $price,
                'attributes' => [
                    'image' => $image,
                    'url' => $url,
                    'variant_id' => $variantId,
                    'variant_attributes' => $variantAttributes,
                    'variant_label' => $variantLabel,
                ]
            ]);

            $response = $this->mini()->getData(true);
            $response['success'] = true;
            $response['message'] = 'Product added to cart successfully!';
            // Echo selected attribute ids for debugging (client may send various shapes)
            $response['selected_attributes'] = $selectedAttrIds;

            if ($request->has('redirect')) {
                $response['redirect'] = $request->redirect;
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /* UPDATE QTY */
    public function updateQty(Request $request)
    {
        $request->validate([
            'id'  => 'required',
            'qty' => 'required|integer|min:1',
        ]);

        $this->cart()->update($request->id, [
            'quantity' => [
                'relative' => false,
                'value'    => $request->qty,
            ],
        ]);

        return $this->mini();
    }

    /* REMOVE ITEM */
    public function remove($id)
    {
        $this->cart()->remove($id);
        return $this->mini();
    }

    /* MINI CART JSON */
    public function mini()
    {
        try {
            $cart = $this->cart();
            return response()->json([
                'success'  => true,
                'count'    => $cart->getContent()->count(),
                'subtotal' => number_format((float)$cart->getSubTotal(), 2),
                'items'    => $cart->getContent()->values(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart loading issue',
                'count' => 0,
                'subtotal' => '0.00',
                'items' => []
            ], 200);
        }
    }
}
