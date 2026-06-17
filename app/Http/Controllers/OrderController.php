<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Courier;
use App\Models\User;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $baseQuery = Order::where('is_requisition', false);

        $status = request('status');
        if ($status !== null && $status !== '') {
            $baseQuery->where('status', $status);
        }

        $search = trim((string) request('search', ''));
        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search) {
                $query->where('invoice_no', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_address', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%");
            });
        }

        $orders = (clone $baseQuery)
            ->latest()
            ->with(['store', 'customer', 'assignedTo', 'courier', 'items.product'])
            ->paginate(10)
            ->appends(request()->query());

        $orderCounts = Order::where('is_requisition', false)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalOrders = Order::where('is_requisition', false)->count();

        $orderStatuses = $this->orderStatuses();
        $couriers = Courier::where('status', 1)->get(['id', 'name']);
        $employees = User::where('is_admin', false)->get(['id', 'name']);

        return view('backend.orders.index', compact('orders', 'orderCounts', 'totalOrders', 'orderStatuses', 'couriers', 'employees', 'status', 'search'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $customers = Customer::get(['id', 'name', 'phone']);
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

        $couriers = Courier::where('status', 1)->get(['id', 'name']);

        return view('backend.orders.create', compact(
            'customers', 'stores', 'products', 'employees',
            'paymentMethods', 'paymentStatuses', 'orderStatuses', 'productsVariantData', 'couriers'
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

        $customers = Customer::get(['id', 'name', 'phone']);
        $stores = Store::where('status', 1)->get(['id', 'name']);
        $products = Product::where('status', 1)
            ->with(['variants.variantItems.attributeItem'])
            ->get(['id', 'name', 'sale_price', 'sku', 'has_variant']);
        $employees = User::where('is_admin', false)->get(['id', 'name']);
        $couriers = Courier::where('status', 1)->get(['id', 'name']);

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
            'order', 'customers', 'stores', 'products', 'employees', 'couriers',
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
            'remarks' => 'nullable|string',
            'courier_id' => 'nullable|exists:couriers,id',
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
                $productId  = $itemData['product_id'] ?? null;
                $product    = $productId ? Product::find($productId) : null;
                $hasVariant = $product && $product->has_variant;

                // Extract and remove 'id' — never pass to ->update(), causes PDO binding mismatch
                $itemId = $itemData['id'] ?? null;
                unset($itemData['id']);

                // Skip placeholder rows that have no product
                if (!$productId) {
                    continue;
                }

                // Decode attributes from JSON string to PHP array
                $decodedAttributes = null;
                if (isset($itemData['attributes']) && is_string($itemData['attributes'])) {
                    $decoded = json_decode($itemData['attributes'], true);
                    $decodedAttributes = is_array($decoded) ? $decoded : null;
                } elseif (isset($itemData['attributes']) && is_array($itemData['attributes'])) {
                    $decodedAttributes = $itemData['attributes'];
                }

                if (!$hasVariant) {
                    // Non-variant: prevent duplicates by consolidating into the first row
                    if (isset($nonVariantSeen[$productId])) {
                        OrderItem::where('id', $nonVariantSeen[$productId])
                            ->increment('quantity', (int) $itemData['quantity']);
                        if ($itemId) {
                            OrderItem::where('id', $itemId)->delete();
                        }
                        continue;
                    }
                }

                if ($itemId) {
                    // Raw DB Query Builder update() to bypass Eloquent model casting issues
                    DB::table('order_items')->where('id', $itemId)->update([
                        'order_id'       => $order->id,
                        'product_id'     => $productId,
                        'sku'            => $itemData['sku'] ?? null,
                        'quantity'       => $itemData['quantity'] ?? 1,
                        'purchase_price' => $itemData['purchase_price'] ?? 0,
                        'sale_price'     => $itemData['sale_price'] ?? 0,
                        'attributes'     => $decodedAttributes !== null ? json_encode($decodedAttributes) : null,
                        'updated_at'     => now(),
                    ]);
                    if (!$hasVariant) {
                        $nonVariantSeen[$productId] = $itemId;
                    }
                } else {
                    // Model create() — model casting handles array -> JSON automatically
                    $newItem = OrderItem::create([
                        'order_id'       => $order->id,
                        'product_id'     => $productId,
                        'sku'            => $itemData['sku'] ?? null,
                        'quantity'       => $itemData['quantity'] ?? 1,
                        'purchase_price' => $itemData['purchase_price'] ?? 0,
                        'sale_price'     => $itemData['sale_price'] ?? 0,
                        'attributes'     => $decodedAttributes,
                    ]);
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

    public function bulkStatus(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|string',
            'bulk_status' => 'required|integer',
        ]);

        $orderIds = $this->parseOrderIds($validated['order_ids']);

        if (empty($orderIds)) {
            return back()->withErrors(['Please select at least one order.']);
        }

        $status = (int) $validated['bulk_status'];
        if (!array_key_exists($status, $this->orderStatuses())) {
            return back()->withErrors(['Invalid status selected.']);
        }

        Order::whereIn('id', $orderIds)->update(['status' => $status]);

        return back()->with('success', 'Selected orders updated successfully.');
    }

    public function bulkAssign(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|string',
            'bulk_assign' => 'required|exists:users,id',
        ]);

        $orderIds = $this->parseOrderIds($validated['order_ids']);

        if (empty($orderIds)) {
            return back()->withErrors(['Please select at least one order.']);
        }

        Order::whereIn('id', $orderIds)->update(['assigned_to' => $validated['bulk_assign']]);

        return back()->with('success', 'Selected orders assigned successfully.');
    }

    public function courierExport(Request $request)
    {
        $validated = $request->validate([
            'order_ids' => 'required|string',
            'courier_export' => 'nullable',
        ]);

        $orderIds = $this->parseOrderIds($validated['order_ids']);

        if (empty($orderIds)) {
            return back()->withErrors(['Please select at least one order.']);
        }

        $courierId = !empty($validated['courier_export']) ? (int) $validated['courier_export'] : null;
        if ($courierId) {
            $courierExists = Courier::where('id', $courierId)->exists();
            if (!$courierExists) {
                return back()->withErrors(['Selected courier does not exist.']);
            }
            $courier = Courier::find($courierId);
            $courierName = $courier?->name ?? 'selected courier';
        } else {
            $courier = null;
            $courierName = 'respective couriers';
        }

        $orders = Order::whereIn('id', $orderIds)
            ->with(['items.product', 'store', 'courier'])
            ->get();

        if ($orders->isEmpty()) {
            return back()->withErrors(['Please select at least one order.']);
        }

        $successCount = 0;
        $failedMessages = [];

        foreach ($orders as $order) {
            if ($courier) {
                $order->update(['courier_id' => $courier->id]);
            }

            if (!$order->courier_id) {
                $failedMessages[] = $order->invoice_no . ': Courier not selected for this order';
                continue;
            }

            $result = $this->dispatchOrderToCourier($order->fresh(['items.product', 'store', 'courier']));

            if (!empty($result['status'])) {
                $successCount++;
                continue;
            }

            $failedMessages[] = $order->invoice_no . ': ' . ($result['message'] ?? 'Courier dispatch failed');
        }

        if ($successCount > 0 && empty($failedMessages)) {
            return back()->with('success', $successCount . ' order(s) sent to ' . $courierName . ' successfully.');
        }

        if ($successCount > 0) {
            return back()->with('warning', $successCount . ' order(s) sent to ' . $courierName . '. ' . implode(' | ', array_slice($failedMessages, 0, 3)));
        }

        return back()->withErrors([implode(' | ', array_slice($failedMessages, 0, 3)) ?: ('Unable to send orders to ' . $courierName . '.')]);
    }

    /**---------------------------------------------------------------------------
     * COURIER INTEGRATION METHODS
     * (Called from OrderController and AdminController for single order dispatch)
     *-----------------------------------------------------------------------------
     */
    public function sendToCourierIndex(Request $request)
    {
        // dd($request->all());
        $orderIds = $this->parseOrderIds($request->order_ids ?? '');
        if (empty($orderIds)) {
            return response()->json(['status' => false, 'message' => 'No orders found.'], 422);
        }

        $orders = Order::whereIn('id', $orderIds)
            ->with(['items.product', 'store', 'courier'])
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'No orders found.'], 404);
        }

        $couriers = Courier::where('status', 1)->get(['id', 'name']);

        return view('backend.orders.send-courier', compact('orders', 'couriers'));
    }

    public function sendToCourierItems(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'courier_id' => 'nullable|exists:couriers,id',
        ]);

        $order = Order::with(['items.product', 'store', 'courier'])->find($validated['order_id']);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found',
                'courier' => null,
                'consignment_id' => null,
            ], 404);
        }

        if (!empty($validated['courier_id'])) {
            $order->update(['courier_id' => (int) $validated['courier_id']]);
            $order->refresh();
        }

        return response()->json($this->dispatchOrderToCourier($order));
    }

    private function dispatchOrderToCourier(Order $order): array
    {
        $order->loadMissing(['items.product', 'store', 'courier']);

        if (!$order->courier_id) {
            return [
                'status' => false,
                'message' => 'Courier not selected',
                'courier' => null,
                'consignment_id' => null,
            ];
        }

        if ($order->consignment_id) {
            return [
                'status' => true,
                'message' => 'Already sent' . ($order->consignment_id ? ' (ID: ' . $order->consignment_id . ')' : ''),
                'courier' => $order->courier?->name ?? 'Unknown',
                'consignment_id' => $order->consignment_id,
            ];
        }

        $store = $order->store;
        if (!$store) {
            return [
                'status' => false,
                'message' => 'Store not found for this order',
                'courier' => $order->courier?->name ?? 'Unknown',
                'consignment_id' => null,
            ];
        }

        switch ($order->courier->slug ?? null) {
            case 'steadfast':
                return $this->dispatchSteadfastOrder($order, $store);

            case 'pathao':
                return $this->dispatchPathaoOrder($order, $store);

            case 'carrybee':
                return $this->dispatchCarrybeeOrder($order, $store);
        }

        return [
            'status' => false,
            'message' => 'Courier integration is not configured for this courier',
            'courier' => $order->courier?->name ?? 'Unknown',
            'consignment_id' => null,
        ];
    }

    private function dispatchSteadfastOrder(Order $order, Store $store): array
    {
        $courierSetting = $order->courier->settingForStore($store->id);

        if (!$courierSetting || $courierSetting->status !== 1) {
            return [
                'status' => false,
                'message' => 'Steadfast is not active for this store',
                'courier' => 'Steadfast',
                'consignment_id' => null,
            ];
        }

        $payload = [
            'invoice'           => $order->invoice_no ?? null,
            'recipient_name'    => $order->customer_name ?? null,
            'recipient_phone'   => $order->customer_phone ?? null,
            'recipient_address' => $order->customer_address ?? null,
            'cod_amount'        => (int) round($order->due ?? 0),
            'note'              => $order->notes ?? $order->remarks ?? null,
        ];

        $response = Http::withHeaders([
            'Api-Key'    => $courierSetting->api_key ?? '',
            'Secret-Key' => $courierSetting->secret_key ?? '',
            'Accept'     => 'application/json',
        ])->post('https://portal.packzy.com/api/v1/create_order', $payload);

        $data = $response->json();

        if ($response->successful() && data_get($data, 'status') == 200) {

            $consignmentId = data_get($data, 'consignment.consignment_id');
            $trackingCode  = data_get($data, 'consignment.tracking_code');
            $trackingLink  = data_get($data, 'consignment.tracking_link');

            $order->update([
                'status'         => 7,
                'consignment_id' => $consignmentId,
                'tracking_code'  => $trackingCode,
                'tracking_url'   => $trackingLink,
                'courier_id'     => $order->courier_id,
            ]);

            return [
                'status' => true,
                'message' => 'Sent to Steadfast' . ($consignmentId ? " ($consignmentId)" : ''),
                'courier' => 'Steadfast',
                'consignment_id' => $consignmentId,
            ];
        }

        return [
            'status' => false,
            'message' => data_get($data, 'message') ?? 'Steadfast order creation failed',
            'courier' => 'Steadfast',
            'consignment_id' => null,
            'debug' => $data, // 👈 add this for debugging
        ];
    }

    private function dispatchPathaoOrder(Order $order, Store $store): array
    {
        if ($order->courier->settingForStore($store->id)->status !== 1) {
            return [
                'status' => false,
                'message' => 'Pathao is not active for this store',
                'courier' => 'Pathao',
                'consignment_id' => null,
            ];
        }

        $courierSetting = $order->courier->settingForStore($store->id);

        $parserResponse = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($courierSetting->access_token ?? ''),
        ])->post('https://merchant.pathao.com/api/v1/address-parser', [
            'address' => $order->customer_address ?? '',
        ]);

        $parserData = $parserResponse->json();
        $cityId = data_get($parserData, 'data.district_id');
        $zoneId = data_get($parserData, 'data.zone_id');

        if (!$parserResponse->successful() || !$cityId || !$zoneId) {
            return [
                'status' => false,
                'message' => 'Unable to resolve Pathao city/zone from the customer address',
                'courier' => 'Pathao',
                'consignment_id' => null,
            ];
        }

        $itemDescription = $this->buildOrderItemDescription($order);
        $payload = [
            'store_id' => $courierSetting->store_code ?? null,
            'merchant_order_id' => $order->invoice_no ?? null,
            'recipient_name' => $order->customer_name ?? null,
            'recipient_phone' => $order->customer_phone ?? null,
            'recipient_address' => $order->customer_address ?? null,
            'recipient_city' => $cityId,
            'recipient_zone' => $zoneId,
            'recipient_area' => null,
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => $order->notes ?? $order->remarks ?? null,
            'item_quantity' => (int) $order->items->sum('quantity') ?: 1,
            'item_weight' => 0.5,
            'amount_to_collect' => (int) round($order->due ?? 0),
            'item_description' => $itemDescription ?: null,
        ];

        $response = Http::withHeaders([
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'authorization' => 'Bearer ' . ($courierSetting->access_token ?? ''),
        ])->post('https://api-hermes.pathao.com/aladdin/api/v1/orders', $payload);

        $responseData = $response->json();
        $consignmentId = data_get($responseData, 'data.consignment_id');

        if ($response->successful() && (int) data_get($responseData, 'code') === 200 && $consignmentId) {
            $order->update([
                'status' => 7,
                'consignment_id' => $consignmentId,
                'tracking_url' => 'https://merchant.pathao.com/tracking?consignment_id=' . $consignmentId,
                'courier_id' => $order->courier_id,
            ]);

            return [
                'status' => true,
                'message' => 'Sent to Pathao' . ' (Consignment ID: ' . $consignmentId . ')',
                'courier' => 'Pathao',
                'consignment_id' => $consignmentId,
            ];
        }

        return [
            'status' => false,
            'message' => data_get($responseData, 'message') ?? 'Pathao order creation failed',
            'courier' => 'Pathao',
            'consignment_id' => null,
        ];
    }

    private function dispatchCarrybeeOrder(Order $order, Store $store): array
    {
        if ($order->courier->settingForStore($store->id)->status !== 1) {
            return [
                'status' => false,
                'message' => 'Carrybee is not active for this store',
                'courier' => 'Carrybee',
                'consignment_id' => null,
            ];
        }

        $courierSetting = $order->courier->settingForStore($store->id);

        // $parserResponse = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer ' . ($courierSetting->access_token ?? ''),
        // ])->post('https://api-merchant.carrybee.com/api/v2/businesses/206/address-parser', [
        //     'query' => $order->customer_address ?? '',
        // ]);

        // $parserData = $parserResponse->json();
        // $cityId = data_get($parserData, 'data.city_id');
        // $zoneId = data_get($parserData, 'data.zone_id');

        // if (!$parserResponse->successful() || !$cityId || !$zoneId) {
        //     return [
        //         'status' => false,
        //         'message' => 'Unable to resolve Carrybee city/zone from the customer address',
        //         'courier' => 'Carrybee',
        //         'consignment_id' => null,
        //     ];
        // }

        $itemDescription = $this->buildOrderItemDescription($order);
        $payload = [
            'store_id' => $courierSetting->store_code ?? null,
            'merchant_order_id' => $order->invoice_no ?? null,
            'delivery_type' => 1,
            'product_type' => 1,
            'recipient_secendary_phone' => null,
            'recipient_name' => $order->customer_name ?? null,
            'recipient_phone' => $order->customer_phone ?? null,
            'recipient_address' => $order->customer_address ?? null,
            'city_id' => $cityId ?? null,
            'zone_id' => $zoneId ?? null,
            'area_id' => null,
            'special_instruction' => $order->notes ?? $order->remarks ?? null,
            'product_description' => $itemDescription ?: null,
            'item_weight' => 1,
            'item_quantity' => (int) $order->items->sum('quantity') ?: 1,
            'collectable_amount' => (float) ($order->due ?? 0),
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Client-ID' => $courierSetting->client_id ?? '',
            'Client-Secret' => $courierSetting->client_secret ?? '',
            'Client-Context' => $courierSetting->client_context ?? '',
        ])->post('https://developers.carrybee.com/api/v2/orders', $payload);

        $responseData = $response->json();
        $consignmentId = data_get($responseData, 'data.order.consignment_id');

        if ($response->successful() && !data_get($responseData, 'error') && $consignmentId) {
            $order->update([
                'status' => 7,
                'consignment_id' => $consignmentId,
                'tracking_url' => 'https://merchant.carrybee.com/order-track/' . $consignmentId,
                'courier_id' => $order->courier_id,
            ]);

            return [
                'status' => true,
                'message' => 'Sent to Carrybee' . ($consignmentId ? ' (Consignment ID: ' . $consignmentId . ')' : ''),
                'courier' => 'Carrybee',
                'consignment_id' => $consignmentId,
            ];
        }

        return [
            'status' => false,
            'message' => data_get($responseData, 'message') ?? 'Carrybee order creation failed',
            'courier' => 'Carrybee',
            'consignment_id' => null,
        ];
    }

    private function buildOrderItemDescription(Order $order): string
    {
        return $order->items
            ->map(function ($item) {
                $productName = $item->product->name ?? ('SKU: ' . ($item->sku ?? 'Unknown'));
                return $item->quantity . ' x ' . $productName;
            })
            ->implode("\n");
    }














    /*-----------------------------------------------------------
     * Helper methods for order management
     * ----------------------------------------------------------
     */

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

    private function parseOrderIds(string $orderIds): array
    {
        return collect(explode(',', $orderIds))
            ->map(fn ($id) => (int) trim($id))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
