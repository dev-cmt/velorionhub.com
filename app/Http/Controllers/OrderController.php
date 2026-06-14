<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User; // For Customer and Assigned User
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::latest()
            ->where('is_requisition', false)
            ->with(['store', 'customer', 'assignedTo', 'items.product'])
            ->paginate(10);

        return view('backend.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = User::get(['id', 'name', 'phone']);
        $stores = Store::where('status', 1)->get(['id', 'name']);
        $products = Product::where('status', 1)
            ->with(['variants.variantItems.attributeItem'])
            ->get(['id', 'name', 'sale_price', 'sku', 'has_variant']);
        $employees = User::where('is_admin', false)->get(['id', 'name']);

        $paymentMethods = $this->paymentMethods();
        $paymentStatuses = $this->paymentStatuses();
        $orderStatuses = $this->orderStatuses();

        // Build variant map for JS: productId => [variants...]
        $productsVariantData = $products->mapWithKeys(function ($p) {
            return [$p->id => [
                'has_variant' => (bool) $p->has_variant,
                'variants'    => $p->has_variant ? $p->variant_summary : [],
            ]];
        });

        return view('backend.orders.create', compact(
            'customers', 'stores', 'products', 'employees',
            'paymentMethods', 'paymentStatuses', 'orderStatuses', 'productsVariantData'
        ));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate Order Data
        $orderValidated = $request->validate([
            'invoice_no' => 'required|string|max:50|unique:orders,invoice_no',
            'source' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'store_id' => 'required|exists:stores,id',
            'assigned_to' => 'nullable|exists:users,id',

            // Financials (calculated on front-end, validated here)
            'sub_total' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',

            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // 2. Validate Order Items Data
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.sku' => 'required|string|max:50',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.sale_price' => 'required|numeric|min:0',
            // 'items.*.attributes' => 'nullable|array', // Laravel will handle this as JSON
        ]);

        try {
            DB::beginTransaction();

            // 3. Create the Order
            $order = Order::create($orderValidated);

            // 4. Create Order Items (variant products: allow duplicates; non-variant: consolidate)
            $nonVariantSeen = []; // product_id => row index for deduplication
            $orderItems = [];
            foreach ($request->input('items') as $item) {
                $productId  = $item['product_id'];
                $variantSku = trim($item['sku'] ?? '');
                $product    = Product::find($productId);
                $hasVariant = $product && $product->has_variant;

                if ($hasVariant) {
                    // Variant products: always add as a new line (keyed by SKU)
                    $orderItems[] = new OrderItem([
                        'order_id'       => $order->id,
                        'product_id'     => $productId,
                        'sku'            => $variantSku ?: ($product->sku ?? ''),
                        'quantity'       => $item['quantity'],
                        'purchase_price' => $item['purchase_price'],
                        'sale_price'     => $item['sale_price'],
                        'attributes'     => isset($item['attributes']) ? (is_string($item['attributes']) ? json_decode($item['attributes'], true) : $item['attributes']) : null,
                    ]);
                } else {
                    // Non-variant products: consolidate duplicates (sum quantities)
                    if (isset($nonVariantSeen[$productId])) {
                        $idx = $nonVariantSeen[$productId];
                        $orderItems[$idx]->quantity += (int) $item['quantity'];
                    } else {
                        $nonVariantSeen[$productId] = count($orderItems);
                        $orderItems[] = new OrderItem([
                            'order_id'       => $order->id,
                            'product_id'     => $productId,
                            'sku'            => $variantSku ?: ($product->sku ?? ''),
                            'quantity'       => $item['quantity'],
                            'purchase_price' => $item['purchase_price'],
                            'sale_price'     => $item['sale_price'],
                            'attributes'     => null,
                        ]);
                    }
                }
            }
            $order->items()->saveMany($orderItems);

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully. Invoice No: ' . $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['An error occurred while placing the order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($order)
    {
        $order = Order::with('items.product')->find($order);

        $customers = User::get(['id', 'name', 'phone']);
        $stores = Store::where('status', 1)->get(['id', 'name']);
        $products = Product::where('status', 1)
            ->with(['variants.variantItems.attributeItem'])
            ->get(['id', 'name', 'sale_price', 'sku', 'has_variant']);
        $employees = User::where('is_admin', false)->get(['id', 'name']);

        $paymentMethods = $this->paymentMethods();
        $paymentStatuses = $this->paymentStatuses();
        $orderStatuses = $this->orderStatuses();

        // Build variant map for JS
        $productsVariantData = $products->mapWithKeys(function ($p) {
            return [$p->id => [
                'has_variant' => (bool) $p->has_variant,
                'variants'    => $p->has_variant ? $p->variant_summary : [],
            ]];
        });

        return view('backend.orders.edit', compact(
            'order', 'customers', 'stores', 'products', 'employees',
            'paymentMethods', 'paymentStatuses', 'orderStatuses', 'productsVariantData'
        ));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        // dd($request->all());
        // 1. Validate Order Data
        $orderValidated = $request->validate([
            'source' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'store_id' => 'required|exists:stores,id',
            'assigned_to' => 'nullable|exists:users,id',

            // Financials
            'sub_total' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',

            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        // 2. Validate Order Items Data
        // $request->validate([
        //     'items'                  => 'required|array|min:1',
        //     'items.*.id'             => 'nullable|exists:order_items,id',
        //     'items.*.product_id'     => 'required|exists:products,id',
        //     'items.*.sku'            => 'nullable|string|max:100',
        //     'items.*.quantity'       => 'required|integer|min:1',
        //     'items.*.purchase_price' => 'required|numeric|min:0',
        //     'items.*.sale_price'     => 'required|numeric|min:0',
        // ]);

        try {
            DB::beginTransaction();

            // 3. Update the Order
            $order->update($orderValidated);

            // 4. Sync Order Items (variant = allow duplicates; non-variant = consolidate)
            $submittedItemIds = collect($request->input('items'))->pluck('id')->filter()->toArray();
            $order->items()->whereNotIn('id', $submittedItemIds)->delete();

            $nonVariantSeen = []; // product_id => order_item_id for deduplication
            foreach ($request->input('items') as $itemData) {
                $itemData['order_id'] = $order->id;
                $productId  = $itemData['product_id'];
                $product    = Product::find($productId);
                $hasVariant = $product && $product->has_variant;

                if (isset($itemData['attributes']) && is_string($itemData['attributes'])) {
                    $itemData['attributes'] = json_decode($itemData['attributes'], true) ?: null;
                } elseif (isset($itemData['attributes']) && is_array($itemData['attributes'])) {
                    // keep as array, let model cast handle it
                } else {
                    $itemData['attributes'] = null;
                }

                if (!$hasVariant) {
                    // Non-variant: prevent duplicates by consolidating into the first row
                    if (isset($nonVariantSeen[$productId])) {
                        // Add quantity to the already-saved item
                        OrderItem::where('id', $nonVariantSeen[$productId])
                            ->increment('quantity', (int) $itemData['quantity']);
                        // Delete the current row if it was an existing item (avoid orphan)
                        if (isset($itemData['id'])) {
                            OrderItem::where('id', $itemData['id'])->delete();
                        }
                        continue;
                    }
                }

                if (isset($itemData['id']) && $itemData['id']) {
                    OrderItem::where('id', $itemData['id'])->update($itemData);
                    if (!$hasVariant) {
                        $nonVariantSeen[$productId] = $itemData['id'];
                    }
                } else {
                    $newItem = OrderItem::create($itemData);
                    if (!$hasVariant) {
                        $nonVariantSeen[$productId] = $newItem->id;
                    }
                }
            }

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order updated successfully. Invoice No: ' . $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['An error occurred while updating the order: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Delete all associated order items first
            $order->items()->delete();

            // Then delete the order itself
            $order->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['An error occurred while deleting the order.']);
        }
    }

    private function paymentMethods()
    {
        return [
            0 => 'Cash',
            1 => 'Card',
            2 => 'Mobile Banking',
            3 => 'COD',
            4 => 'Bank Transfer'
        ];
    }

    private function paymentStatuses()
    {
        return [
            0 => 'Pending',
            1 => 'Partial',
            2 => 'Paid',
            3 => 'Cancelled'
        ];
    }

    private function orderStatuses()
    {
        return [
            0 => 'Pending',
            1 => 'Confirmed',
            2 => 'Hold',
            3 => 'Cancelled',
            4 => 'Stockout',
            5 => 'Packaged',
            6 => 'Courier Entry',
            7 => 'On Delivery',
            8 => 'Delivered',
            9 => 'Partial Delivered',
            10 => 'Exchange',
            11 => 'Return',
            12 => 'Return Received',
        ];
    }
}
