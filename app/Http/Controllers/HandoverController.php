<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exports\HandoverExport;
use App\Models\Handover;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\SyncsVariantStock;

class HandoverController extends Controller
{
    use SyncsVariantStock;
    public function index(Request $request)
    {
        $courier_id = $request->input('courier');

        // Parcels with is_temp = 1 (finalised handovers)
        $parcelsQuery = DB::table('handovers as ph')
            ->join('orders as o', 'ph.order_id', '=', 'o.id')
            ->join('couriers as c', 'o.courier_id', '=', 'c.id')
            ->select('ph.*', 'o.id as order_id', 'o.invoice_no', 'o.courier_id', 'o.status', 'c.name as courier_name')
            ->where('ph.is_temp', 1);

        if ($courier_id) {
            $parcelsQuery->where('o.courier_id', $courier_id);
        }
        $parcels = $parcelsQuery->orderBy('ph.created_at', 'desc')->get();

        // Processing parcels with is_temp = 3 (scanned but not yet finalised)
        $processing = DB::table('handovers as ph')
            ->join('orders as o', 'ph.order_id', '=', 'o.id')
            ->join('couriers as c', 'o.courier_id', '=', 'c.id')
            ->select('ph.*', 'o.id as order_id', 'o.invoice_no', 'o.courier_id', 'o.status', 'c.name as courier_name')
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

        $order = Order::where('invoice_no', $request->input('scaning'))
            ->select('id', 'invoice_no', 'status', 'courier_id')
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Invalid Invoice Number'], 400);
        }

        $already_exist = DB::table('handovers')->where('order_id', $order->id)->first();

        if ($order->status == 7 && $order->courier_id != null && !$already_exist) {
            Handover::create([
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

    public function finalHandover(Request $request)
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

            // ✅ Update order status to 8 (handed over)
            $order->update(['status' => 8]);

            /**-----------------------------------------
             * Start - Update stock for each order item
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
                                        $variant->decrement('variant_stock', $qty);
                                        $this->syncVariantTotalStock($parentProduct);
                                    }
                                } else {
                                    $parentProduct->decrement('total_stock', $qty);
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
                                    $variant->decrement('variant_stock', $qty);
                                    $this->syncVariantTotalStock($comboProduct);
                                }
                            } else {
                                $comboProduct->decrement('total_stock', $qty);
                            }
                        }
                    }
                }
                // Variant products (has parent)
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
                                $variant->decrement('variant_stock', $qty);
                                $this->syncVariantTotalStock($parentProduct);
                            }
                        } else {
                            $parentProduct->decrement('total_stock', $qty);
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
                            $variant->decrement('variant_stock', $qty);
                            $this->syncVariantTotalStock($product);
                        }
                    } else {
                        $product->decrement('total_stock', $qty);
                    }
                }
            }

            // ✅ Mark handover as finalised (is_temp 3 → 1)
            Handover::where('order_id', $orderId)->update(['is_temp' => 1]);

            return response()->json([
                'status'         => true,
                'message'        => 'Handover successfully.',
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
        Handover::where('is_temp', 1)->update(['is_temp' => 0]);
        return back()->with('success', 'Cleared Successfully');
    }

    public function print(Request $request)
    {
        $courierId = $request->courier;

        $parcel_Handover = Handover::query()
            ->join('orders', 'orders.id', 'handovers.order_id')
            ->join('couriers', 'couriers.id', 'orders.courier_id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('handovers.is_temp', 1)
            ->select('handovers.created_at', 'orders.courier_id', 'couriers.name')
            ->get();

        $courier_data = $parcel_Handover->groupBy('courier_id')->map(fn($group) => $group->count());

        $sale_items = OrderItem::query()
            ->join('orders', 'orders.id', 'order_items.order_id')
            ->join('handovers', 'handovers.order_id', 'orders.id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
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

        $couriers      = Courier::pluck('name', 'id')->toArray();
        $courier_name  = $courierId ? ($couriers[$courierId] ?? '') : '';
        $max_date      = $parcel_Handover->max('created_at');
        $min_date      = $parcel_Handover->min('created_at');

        return view('backend.handover.print', compact(
            'courier_data', 'sale_items', 'couriers', 'courier_name', 'max_date', 'min_date'
        ));
    }

    public function csvExport(Request $request)
    {
        $courierId = $request->query('courier');
        $courier = DB::table('couriers')->where('id', $courierId)->first();
        $prefix  = $courier ? $courier->name . '_' : '';
        $file_name = $prefix . 'parcel_handover_' . date('d-M-Y-h:i:s_A') . '.csv';

        $handovers = DB::table('handovers')
            ->join('orders', 'handovers.order_id', '=', 'orders.id')
            ->join('couriers', 'orders.courier_id', '=', 'couriers.id')
            ->when($courierId, fn($q) => $q->where('orders.courier_id', $courierId))
            ->where('handovers.is_temp', 1)
            ->select('orders.invoice_no', 'couriers.name as courier_name', 'handovers.created_at')
            ->orderBy('handovers.created_at', 'desc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$file_name",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Invoice No', 'Courier', 'Handover Time'];

        $callback = function() use($handovers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($handovers as $row) {
                fputcsv($file, [
                    $row->invoice_no,
                    $row->courier_name,
                    date('d-m-Y h:i A', strtotime($row->created_at))
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
