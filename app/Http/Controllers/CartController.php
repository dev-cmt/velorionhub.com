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

    private function miniCartResponse($message = null)
    {
        $cart = $this->cart();
        $items = [];

        foreach ($cart->getContent() as $item) {
            $productId = $item->attributes->get('product_id');
            $productModel = null;
            if ($productId) {
                $productModel = Product::find($productId);
            }
            if (!$productModel && $item->associatedModel) {
                $productModel = $item->associatedModel;
            }

            $image = ($productModel && $productModel->main_image)
                ? asset($productModel->main_image)
                : (($item->attributes && isset($item->attributes['image'])) ? $item->attributes['image'] : asset('images/no-image.jpg'));

            $url = ($productModel)
                ? route('product.show', $productModel->slug)
                : (($item->associatedModel)
                    ? route('product.show', $item->associatedModel->slug)
                    : (($item->attributes && isset($item->attributes['url'])) ? $item->attributes['url'] : '#'));

            $items[] = [
                'id' => $item->id,
                'name' => $item->name,
                'price' => floatval($item->price),
                'quantity' => intval($item->quantity),
                'attributes' => array_merge($item->attributes->toArray(), [
                    'image' => $image,
                    'url' => $url,
                ]),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => intval($cart->getTotalQuantity()),
            'items' => $items,
            'subtotal' => floatval($cart->getSubTotal()),
            'total' => floatval($cart->getTotal()),
        ]);
    }

    // ================= GET MINI CART =================
    public function getMiniCart()
    {
        return $this->miniCartResponse();
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

        $attributeIds = $request->input('attributes');

        if (!empty($attributeIds) && is_array($attributeIds)) {
            sort($attributeIds); // normalize
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

                    return $ids == $attributeIds;
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
                    'relative' => true,
                    'value' => $quantity,
                ],
            ]);
        } else {
            $cart->add([
                'id' => $sku,
                'name' => $name,
                'price' => floatval($price),
                'quantity' => intval($quantity),
                'attributes' => [
                    'product_id' => $product->id,
                    'variant_id' => $variant ? $variant->id : null,
                    'variant_key' => $variantKey,
                    'variant_attributes' => $variantAttributes,
                    'variant_label' => $variantLabel,
                ],
                'associatedModel' => $product,
            ]);
        }

        // ================= DATALAYER =================
        $add_to_cart_event = [
            'name' => $product->name,
            'id' => $product->id,
            'price' => $price,
            'category' => $product->category->name ?? '',
            'quantity' => $quantity
        ];
        session()->flash('add_to_cart_event', $add_to_cart_event);

        return $request->ajax()
            ? $this->miniCartResponse('Product added to cart')
            : back()->with('success', 'Product added to cart successfully.');
    }

    // ================= UPDATE QTY =================
    public function updateQty(Request $request)
    {
        $cart = $this->cart();
        $id = $request->id;
        $qty = intval($request->qty ?? $request->quantity ?? 1);

        $cart->update($id, [
            'quantity' => [
                'relative' => false,
                'value' => $qty,
            ],
        ]);

        return $this->miniCartResponse('Quantity updated');
    }

    // ================= PLUS =================
    public function cartItemPlus(Request $request)
    {
        $cart = $this->cart();

        $cart->update($request->id, [
            'quantity' => 1,
        ]);

        return $this->miniCartResponse('Quantity increased');
    }

    // ================= MINUS =================
    public function cartItemMinus(Request $request)
    {
        $cart = $this->cart();

        $cart->update($request->id, [
            'quantity' => -1,
        ]);

        return $this->miniCartResponse('Quantity decreased');
    }

    // ================= REMOVE =================
    public function cartItemRemove(Request $request, $id = null)
    {
        $cart = $this->cart();
        $itemId = $id ?? $request->id;

        $cart->remove($itemId);

        return $this->miniCartResponse('Item removed from cart');
    }

    // ================= CLEAR CART =================
    public function cartClear()
    {
        $cart = $this->cart();
        $cart->clear();

        return $this->miniCartResponse('Cart cleared successfully');
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
