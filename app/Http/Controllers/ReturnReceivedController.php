<?php

namespace App\Http\Controllers;

use App\Exports\ReturnReceivedExport;
use App\Http\Controllers\Controller;
use App\Models\ReturnReceived;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Courier;
use App\Http\Controllers\Traits\SyncsVariantStock;

class ReturnReceivedController extends Controller
{
    use SyncsVariantStock;
    /*public function index(Request $request)
    {
        $data = SaleReturn::with('get_sale.sale_items')->orderBy('id', 'desc')->get();
        $stores = DB::table('stores')->where('status', 1)->pluck('name', 'id');
        //dd($data);
        return view('backend.returns.index', compact('data', 'stores'));
    }*/
    public function __construct()
    {
        $this->paginate_value = 50;
    }
    public function returnReceive(Request $request)
    {
        $courier_id = $request->input('courier');

        // Parcels with is_temp = 1 (finalised return received)
        $parcelsQuery = ReturnReceived::with(['get_sale', 'get_sale.get_courier'])
            ->join('orders as o', 'return_receiveds.sale_id', '=', 'o.id')
            ->select('return_receiveds.*')
            ->where('return_receiveds.is_temp', 1);

        if ($courier_id) {
            $parcelsQuery->where('o.courier_id', $courier_id);
        }
        $parcels = $parcelsQuery->orderBy('return_receiveds.created_at', 'desc')->get();

        // Processing returns with is_temp = 3 (scanned but not finalised)
        $processing = ReturnReceived::with(['get_sale', 'get_sale.get_courier'])
            ->join('orders as o', 'return_receiveds.sale_id', '=', 'o.id')
            ->select('return_receiveds.*')
            ->where('return_receiveds.is_temp', 3)
            ->orderBy('return_receiveds.created_at', 'desc')
            ->get();

        $couriers = DB::table('couriers')->pluck('name', 'id');

        return view('backend.return_received.index', compact('parcels', 'couriers', 'processing'));
    }

    public function addTemp(Request $request)
    {
        if (!$request->input('scaning')) {
            return response()->json(['message' => 'No barcode scanned'], 400);
        }

        $order = Order::where('invoice_no', $request->input('scaning'))
            ->select('id', 'invoice_no', 'status', 'courier_id')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Invalid Invoice Number'], 400);
        }

        $already_exist = DB::table('return_receiveds')->where('sale_id', $order->id)->first();

        if (($order->status == 8 || $order->status == 11) && !$already_exist) {
            ReturnReceived::create([
                'sale_id' => $order->id,
                'order_id' => $order->id,
                'is_temp'  => 3
            ]);

            $courier = DB::table('couriers')->where('id', $order->courier_id)->value('name');
            return response()->json([
                'status'       => true,
                'message'      => 'Added Successfully',
                'order_id'     => $order->id,
                'invoice_no'   => $order->invoice_no ?? '',
                'courier_name' => $courier ?? '---',
                'created_at'   => now()->format('d-m-Y h:i A'),
            ], 200);
        }

        return response()->json(['message' => 'Already Exists or Not Eligible'], 400);
    }

    public function finalReturn(Request $request)
    {
        try {
            $orderId = $request->input('order_id');
            if (!$orderId) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Missing order ID'
                ]);
            }

            $order = Order::with('sale_items')->find($orderId);
            if (!$order) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Order not found'
                ]);
            }

            // ✅ Update order status to 12 (return received)
            $order->update(['status' => 12]);

            /**-----------------------------------------
             * Start - Update stock for each sale item (Increment stock)
             * -----------------------------------------
             */
            foreach ($order->sale_items as $item) {
                $product = Product::find($item->product_id);
                if (!$product) continue;

                $qty = ($item->quantity ?? 0) * ($item->item_out ?? 0);

                $itemAttributes = is_array($item->attributes) ? $item->attributes : json_decode($item->attributes ?? '[]', true);
                if (!is_array($itemAttributes)) {
                    $itemAttributes = [];
                }

                // Combo products
                if ($product->get_combo_products && $product->combo_products) {
                    foreach ($product->get_combo_products as $combo) {
                        $comboProduct = Product::find($combo->product_id);
                        if (!$comboProduct) continue;

                        if ($comboProduct->parent_id) {
                            $parentProduct = Product::find($comboProduct->parent_id);
                            if ($parentProduct) {
                                if ($parentProduct->has_variant) {
                                    $variant = ProductVariant::where('product_id', $parentProduct->id)
                                        ->where('variant_sku', $item->sku)
                                        ->first();

                                    if (!$variant && !empty($itemAttributes)) {
                                        $variant = ProductVariant::where('product_id', $parentProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                            json_encode(array_map('strval', tap((array) $itemAttributes, fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                        ])->first();
                                    }

                                    if ($variant) {
                                        $variant->increment('variant_stock', $qty);
                                        $this->syncVariantTotalStock($parentProduct);
                                    }
                                } else {
                                    $parentProduct->increment('total_stock', $qty);
                                }
                            }
                        } else {
                            if ($comboProduct->has_variant) {
                                $variant = ProductVariant::where('product_id', $comboProduct->id)
                                    ->where('variant_sku', $item->sku)
                                    ->first();

                                if (!$variant && !empty($itemAttributes)) {
                                    $variant = ProductVariant::where('product_id', $comboProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                        json_encode(array_map('strval', tap((array) $itemAttributes, fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                    ])->first();
                                }

                                if ($variant) {
                                    $variant->increment('variant_stock', $qty);
                                    $this->syncVariantTotalStock($comboProduct);
                                }
                            } else {
                                $comboProduct->increment('total_stock', $qty);
                            }
                        }
                    }
                }
                // Variant products / Similar products
                elseif ($product->parent_id) {
                    $parentProduct = Product::find($product->parent_id);
                    if ($parentProduct) {
                        if ($parentProduct->has_variant) {
                            $variant = ProductVariant::where('product_id', $parentProduct->id)
                                ->where('variant_sku', $item->sku)
                                ->first();

                            if (!$variant && !empty($itemAttributes)) {
                                $variant = ProductVariant::where('product_id', $parentProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                    json_encode(array_map('strval', tap((array) $itemAttributes, fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                ])->first();
                            }

                            if ($variant) {
                                $variant->increment('variant_stock', $qty);
                                $this->syncVariantTotalStock($parentProduct);
                            }
                        } else {
                            $parentProduct->increment('total_stock', $qty);
                        }
                    }
                }
                // Simple product
                else {
                    if ($product->has_variant) {
                        $variant = ProductVariant::where('product_id', $product->id)
                            ->where('variant_sku', $item->sku)
                            ->first();

                        if (!$variant && !empty($itemAttributes)) {
                            $variant = ProductVariant::where('product_id', $product->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                json_encode(array_map('strval', tap((array) $itemAttributes, fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                            ])->first();
                        }

                        if ($variant) {
                            $variant->increment('variant_stock', $qty);
                            $this->syncVariantTotalStock($product);
                        }
                    } else {
                        $product->increment('total_stock', $qty);
                    }
                }
            }

            // ✅ Mark return received as finalised (is_temp 3 → 1)
            ReturnReceived::where('sale_id', $orderId)->update(['is_temp' => 1]);

            return response()->json([
                'status'         => true,
                'message'        => 'Return received successfully.',
                'courier'        => optional($order->get_courier)->name ?? null,
                'consignment_id' => $order->invoice_no ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function clearTemp(Request $request)
    {
        // Clear both finalised (is_temp=1) and pending scan (is_temp=3) records
        ReturnReceived::whereIn('is_temp', [1, 3])->update([
            'is_temp' => 0
        ]);
        return back()->with('success', 'Cleared Successfully');
    }


    public function print(Request $request)
    {
        $courierId = $request->courier;

        $return_receiveds = ReturnReceived::query()
            ->join('orders', 'orders.id', 'return_receiveds.sale_id')
            ->join('couriers', 'couriers.id', 'orders.courier_id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('return_receiveds.is_temp', 1)
            ->select('return_receiveds.created_at', 'orders.courier_id', 'couriers.name')
            ->get();

        $courier_data = $return_receiveds->groupBy('courier_id')->map(fn($group) => $group->count());

        $sale_items = OrderItem::query()
            ->join('orders', 'orders.id', 'order_items.order_id')
            ->join('return_receiveds', 'return_receiveds.sale_id', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id') // added
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('return_receiveds.is_temp', 1)
            ->with('get_product:id,name')
            ->select(
                'order_items.product_id',
                'orders.courier_id',
                DB::raw('SUM(order_items.quantity * order_items.item_out) as quantity'),
                DB::raw('SUM(order_items.quantity * order_items.sale_price) as total_price')
            )
            ->groupBy('order_items.product_id', 'orders.courier_id')
            ->get()
            ->groupBy('product_id');

        $couriers = Courier::pluck('name', 'id')->toArray();
        $courier_name = $courierId ? ($couriers[$courierId] ?? '') : '';

        $max_date = $return_receiveds->max('created_at');
        $min_date = $return_receiveds->min('created_at');

        return view('backend.return_received.print', compact(
            'courier_data', 'sale_items', 'couriers', 'courier_name', 'max_date', 'min_date'
        ));
    }

    public function print2(Request $request)
    {
        $courierId = $request->courier;

        // Fetch Parcel Handover with courier info
        $return_receiveds = DB::table('return_receiveds')
            ->join('orders', 'return_receiveds.sale_id', '=', 'orders.id')
            ->join('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('return_receiveds.is_temp', 1)
            ->select(
                'return_receiveds.*',
                'orders.id as sale_id',
                'orders.courier_id',
                'couriers.name as courier_name'
            )
            ->get();

        if ($return_receiveds->isEmpty()) {
            return view('backend.parcel_handover.print2', [
                'sale_items_combined' => collect(),
                'courier_data' => collect(),
                'couriers' => [],
                'courier_name' => '',
                'max_date' => null,
                'min_date' => null,
            ]);
        }

        // Group parcels by courier
        $courier_data = $return_receiveds->groupBy('courier_id')->map(fn($group) => $group->count());

        // Fetch Sale Items with product info including parent
        $sale_items = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('products as parent', 'parent.id', '=', 'products.parent_id')
            ->join('couriers', 'couriers.id', '=', 'orders.courier_id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->whereIn('order_items.order_id', $return_receiveds->pluck('sale_id'))
            ->select(
                'order_items.product_id',
                'products.name as product_name',
                'products.parent_id',
                'parent.name as parent_name',
                'products.combo_products',
                // 'sale_items.quantity',
                DB::raw('(order_items.quantity * order_items.item_out) as quantity'), // added
                'order_items.sale_price as unit_price',
                'orders.courier_id',
                'couriers.name as courier_name'
            )->get();

        $final_items = collect();
        $skipComboProducts = [];

        foreach ($sale_items as $item) {
            // Determine display product: parent if exists, else main
            $displayName = $item->parent_name ?? $item->product_name;
            $displayId = $item->parent_id ?? $item->product_id;

            // If main product has combo_products, skip it later
            if (!empty($item->combo_products)) {
                $skipComboProducts[] = $item->product_id;

                $combo_ids = json_decode($item->combo_products, true);

                if (is_array($combo_ids) && count($combo_ids)) {
                    $combo_products = DB::table('products')->whereIn('id', $combo_ids)->get();

                    foreach ($combo_products as $combo) {
                        $final_items->push([
                            'product_id' => $combo->id,
                            'product_name' => $combo->name,
                            'quantity' => $item->quantity,
                            'unit_price' => $combo->sale_price ?? 0,
                            'courier_id' => $item->courier_id,
                            'courier_name' => $item->courier_name
                        ]);
                    }
                }
            } else {
                // Include main product if no combo_products
                $final_items->push([
                    'product_id' => $displayId,
                    'product_name' => $displayName,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'courier_id' => $item->courier_id,
                    'courier_name' => $item->courier_name
                ]);
            }
        }

        // Remove main products that have combo_products
        $final_items = $final_items->filter(function ($item) use ($skipComboProducts) {
            return !in_array($item['product_id'], $skipComboProducts);
        });

        // Group items by product_id for display
        $sale_items_combined = $final_items->groupBy('product_id')->map(function ($items) {
            return [
                'product_name' => $items->first()['product_name'],
                'rows' => $items
            ];
        });

        // Fetch all couriers
        $couriers = DB::table('couriers')->pluck('name', 'id')->toArray();
        $courier_name = $courierId ? ($couriers[$courierId] ?? '') : '';

        return view('backend.return_received.print2', [
            'sale_items_combined' => $sale_items_combined,
            'courier_data' => $courier_data,
            'couriers' => $couriers,
            'courier_name' => $courier_name,
            'max_date' => $return_receiveds->max('created_at'),
            'min_date' => $return_receiveds->min('created_at'),
        ]);
    }

    // public function print(Request $request)
    // {
    //     $return_receiveds = ReturnReceived::query();
    //     if ($request->courier) {
    //         $return_receiveds->where('sales.courier_id', $request->courier);
    //     }
    //     $return_receiveds = $return_receiveds->join('sales', 'sales.id', 'return_receiveds.sale_id')
    //         ->join('couriers', 'couriers.id', 'sales.courier_id')
    //         ->where('return_receiveds.is_temp', 1)
    //         ->select('return_receiveds.created_at', 'sales.courier_id', 'couriers.name')
    //         ->get();

    //     $max_date = $return_receiveds->max('created_at');
    //     $min_date = $return_receiveds->min('created_at');

    //     $courier_data = [];
    //     foreach ($return_receiveds->groupBy('courier_id', 'couriers.name') as $item) {
    //         $courier_data[$item->first()->name] = $item->count();
    //     }

    //     //dd($return_receiveds);
    //     $data = SaleItem::with(['get_product' => function ($query) {
    //         $query->select('id', 'name');
    //     }])
    //         ->join('sales', 'sales.id', 'sale_items.sale_id')
    //         ->join('return_receiveds', 'return_receiveds.sale_id', 'sales.id')
    //         ->where('return_receiveds.is_temp', 1)
    //         ->select(DB::raw('sale_items.quantity * sale_items.unit_price AS total_price'), 'sales.*', 'sale_items.*', 'sale_items.product_id as s_i_product_id', 'return_receiveds.created_at as r_r_created_at', 'return_receiveds.created_at as is_temp')
    //         ->get()
    //         ->groupBy('s_i_product_id', 'products.name');

    //     //dd($data);
    //     $sale_items = [];
    //     $couriers = DB::table('couriers')->pluck('name', 'id')->toArray();
    //     //dd($couriers);
    //     foreach ($data as $item) {
    //         $c = [];
    //         foreach ($item as $key => $i) {
    //             if (array_key_exists($i->courier_id, $couriers)) {
    //                 if (isset($couriers[$i->courier_id])) {
    //                     $c[$couriers[$i->courier_id]][] = $i->quantity;
    //                 }
    //             }
    //         }
    //         //dd($cont);
    //         $sale_items[$item->first()->get_product->name] = [
    //             'quantity' => $item->sum('quantity'),
    //             'total_price' => $item->sum('total_price'),
    //             'couriers' => $c
    //         ];
    //     }
    //     //dd($sale_items);
    //     return view('backend.return_received.print', compact('courier_data', 'max_date', 'min_date', 'sale_items', 'couriers'))->render();
    //     //return view('backend.parcel_handover.print', compact('data'));
    // }

    public function csvExport(Request $request)
    {
        $name = 'return_received';
        $file_name = $name . '_' . date('d-M-Y-h:i:s_A') . '.xlsx';

        return Excel::download(new ReturnReceivedExport(), $file_name);
    }
}
