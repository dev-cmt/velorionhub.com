<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\HandoverExport;
use App\Models\Handover;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\BinaryOp\NotEqual;

class HandoverController extends Controller
{
    public function index(Request $request)
    {
        $courier_id = $request->input('courier');
        // Parcels with is_temp = 1
        $parcelsQuery = DB::table('handovers as ph')
            ->join('orders as s', 'ph.sale_id', '=', 's.id')
            ->join('couriers as c', 's.courier_id', '=', 'c.id')
            ->select('ph.*', 's.id as sale_id', 's.invoice_no', 's.courier_id', 's.status', 'c.name as courier_name')
            ->where('ph.is_temp', 1);

        if ($courier_id) {
            $parcelsQuery->where('s.courier_id', $courier_id);
        }
        $parcels = $parcelsQuery->orderBy('ph.created_at', 'desc')->get();

        // Processing parcels with is_temp = 3
        $processing = DB::table('handovers as ph')
            ->join('orders as s', 'ph.sale_id', '=', 's.id')
            ->join('couriers as c', 's.courier_id', '=', 'c.id')
            ->select('ph.*', 's.invoice_no', 's.courier_id', 's.status', 'c.name as courier_name')
            ->where('ph.is_temp', 3)
            ->orderBy('ph.created_at', 'desc')
            ->get();

        $couriers = DB::table('couriers')->pluck('name', 'id');

        return view('backend.handover.index', compact('parcels', 'couriers', 'processing'));
    }

    public function addTemp(Request $request)
    {
        if (!$request->input('scaning')) {
            return response()->json(['message' => 'No barcode scanned'], 400);
        }

        $sale = Order::where('invoice_no', $request->input('scaning'))->select('id', 'status', 'courier_id')->first();
        if (!$sale) {
            return response()->json(['message' => 'Invalid Invoice Number'], 400);
        }

        $already_exist = DB::table('handovers')->where('sale_id', $sale->id)->first();
        if ($sale->status == 7 && $sale->courier_id != null && !$already_exist) {
            Handover::create([
                'sale_id' => $sale->id,
                'is_temp' => 3
            ]);
            return response()->json(['message' => 'Added Successfully'], 200);
        }
        return response()->json(['message' => 'Already Exists or Not Eligible'], 400);
    }


    // public function addTemp(Request $request)
    // {
    //     if ($request->input('scaning')) {
    //         $sale = Order::where('invoice_no', $request->input('scaning'))->select('id', 'status', 'courier_id')->first();

    //         if ($sale) {
    //             $already_exist = DB::table('handovers')->where('sale_id', $sale->id)->first();

    //             if ($sale->status == 7 && $sale->courier_id != null && !$already_exist) {
    //                 Handover::create([
    //                     'sale_id' => $sale->id,
    //                     'is_temp' => 3
    //                 ]);
    //                 // return back()->with('success', 'Added Successfully');
    //                 return back()->with('handover_success_msg', 'Added Successfully');
    //             }
    //             return back()->with('already_exist_error', 'Already Exists or Not Eligible');
    //         }
    //         return back()->with('already_exist_error', 'Invalid Invoice Number');
    //     }
    //     return back()->with('already_exist_error', 'No barcode scanned');
    // }

    public function finalHandover(Request $request)
    {
        try {
            // Validation
            $saleId = $request->input('sale_id');
            if (!$saleId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing sale ID'
                ]);
            }

            $sale = Order::with('sale_items')->find($saleId);
            if (!$sale) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sale not found'
                ]);
            }

            // ✅ Update sale status
            $sale->update(['status' => 8]);

            /**-----------------------------------------
             * Start - Update stock for each sale item
             * -----------------------------------------
             */
            foreach ($sale->sale_items as $item) {
                $product = Product::find($item->product_id);
                if (!$product) continue;

                $qty = ($item->quantity ?? 0) * ($item->item_out ?? 0);

                // Combo products
                if ($product->get_combo_products && $product->combo_products) {
                    foreach ($product->get_combo_products as $combo) {
                        $comboProduct = Product::find($combo->product_id);

                        if ($comboProduct->parent_id) {
                            $parentProduct = Product::find($comboProduct->parent_id);
                            if ($parentProduct){
                                $parentProduct->decrement('total_stock', $qty);
                                if ($parentProduct->has_variant) {
                                    optional(
                                        ProductAttribute::where('product_id', $parentProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                            json_encode(array_map('strval', tap((array) json_decode($item->attributes, true), fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                        ])->first()
                                        // ?? $parentProduct->get_product_attributes()->first()
                                    )->decrement('variant_stock', $qty);
                                }
                            }
                        }else{
                            $comboProduct->decrement('total_stock', $qty);
                            if ($comboProduct->has_variant) {
                                optional(
                                    ProductAttribute::where('product_id', $comboProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                        json_encode(array_map('strval', tap((array) json_decode($item->attributes, true), fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                    ])->first()
                                    // ?? $comboProduct->get_product_attributes()->first()
                                )->decrement('variant_stock', $qty);
                            }
                        }
                    }
                }
                // Similar products
                elseif ($product->parent_id) {
                    $parentProduct = Product::find($product->parent_id);
                    if ($parentProduct) {
                        $parentProduct->decrement('total_stock', $qty);
                        if ($parentProduct->has_variant) {
                            optional(
                                ProductAttribute::where('product_id', $parentProduct->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                    json_encode(array_map('strval', tap((array) json_decode($item->attributes, true), fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                                ])->first()
                                // ?? $parentProduct->get_product_attributes()->first()
                            )->decrement('variant_stock', $qty);
                        }
                    }

                }
                // Normal product
                else {
                    $product->decrement('total_stock', $qty);
                    if ($product->has_variant) {
                        optional(
                            ProductAttribute::where('product_id', $product->id)->whereRaw('BINARY attribute_item_ids = ?', [
                                json_encode(array_map('strval', tap((array) json_decode($item->attributes, true), fn(&$arr) => sort($arr))), JSON_UNESCAPED_SLASHES)
                            ])->first()
                            // ?? $product->get_product_attributes()->first()
                        )->decrement('variant_stock', $qty);
                    }
                }
            }
            // ✅ Remove from temp handover list
            Handover::where('sale_id', $saleId)->update(['is_temp' => 1]);

            /**------------------------------------------
             * API call for updating store data
             * ------------------------------------------*/
            // $store = DB::table('stores')->where('id', $sale->store_id)->select('id', 'status', 'ep_order_status', 'base_url')->first();
            // if ($store && $store->status == 1) {
            //     $url = $store->base_url . $store->ep_order_status . '?status=8&invoice_no=' . $sale->invoice_no;
            //     $response = api_call($url, 'GET', null);
            // }

            return response()->json([
                'status' => true,
                'message' => 'Handover successfully.',
                'courier' => optional($sale->get_courier)->name ?? null,
                'consignment_id' => $sale->invoice_no ?? null,
                // 'api_response' => $response,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function clearTemp(Request $request)
    {
        Handover::where('is_temp', 1)->update([
            'is_temp' => 0
        ]);
        return back()->with('success', 'Cleared Successfully');
    }

    public function print(Request $request)
    {
        $courierId = $request->courier;

        $parcel_Handover = Handover::query()
            ->join('orders', 'orders.id', 'handovers.sale_id')
            ->join('couriers', 'couriers.id', 'orders.courier_id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('handovers.is_temp', 1)
            ->select('handovers.created_at', 'orders.courier_id', 'couriers.name')
            ->get();

        $courier_data = $parcel_Handover->groupBy('courier_id')->map(fn($group) => $group->count());

        $sale_items = OrderItem::query()
            ->join('orders', 'orders.id', 'order_items.order_id')
            ->join('handovers', 'handovers.sale_id', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id') // added
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('handovers.is_temp', 1)
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

        $max_date = $parcel_Handover->max('created_at');
        $min_date = $parcel_Handover->min('created_at');

        return view('backend.handover.print', compact(
            'courier_data', 'sale_items', 'couriers', 'courier_name', 'max_date', 'min_date'
        ));
    }

    public function print2(Request $request)
    {
        $courierId = $request->courier;

        // Fetch Parcel Handover with courier info
        $parcel_Handover = DB::table('handovers')
            ->join('orders', 'handovers.sale_id', '=', 'orders.id')
            ->join('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('handovers.is_temp', 1)
            ->select(
                'handovers.*',
                'orders.id as sale_id',
                'orders.courier_id',
                'couriers.name as courier_name'
            )
            ->get();

        if ($parcel_Handover->isEmpty()) {
            return view('backend.handover.print2', [
                'sale_items_combined' => collect(),
                'courier_data' => collect(),
                'couriers' => [],
                'courier_name' => '',
                'max_date' => null,
                'min_date' => null,
            ]);
        }

        // Group parcels by courier
        $courier_data = $parcel_Handover->groupBy('courier_id')->map(fn($group) => $group->count());

        // Fetch Sale Items with product info including parent
        $sale_items = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('products as parent', 'parent.id', '=', 'products.parent_id')
            ->join('couriers', 'couriers.id', '=', 'orders.courier_id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->whereIn('order_items.order_id', $parcel_Handover->pluck('sale_id'))
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

        return view('backend.handover.print2', [
            'sale_items_combined' => $sale_items_combined,
            'courier_data' => $courier_data,
            'couriers' => $couriers,
            'courier_name' => $courier_name,
            'max_date' => $parcel_Handover->max('created_at'),
            'min_date' => $parcel_Handover->min('created_at'),
        ]);
    }


    public function csvExport(Request $request)
    {
        $courier_name = DB::table('couriers')->where('id', $request->query('courier'))->first();
        if ($courier_name) {
            $courier_name = $courier_name->name;
            $courier_name = $courier_name . '_';
        }
        $name = 'parcel_handover';
        $file_name = $courier_name . $name . '_' . date('d-M-Y-h:i:s_A') . '.xlsx';

        return Excel::download(new HandoverExport($request->query('courier')), $file_name);
    }
}
