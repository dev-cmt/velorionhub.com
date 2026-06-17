<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['supplier', 'items'])->orderBy('id', 'desc')->paginate(50);
        return view('backend.purchase.index', compact('purchases'));
    }

    public function create()
    {
        $data = [
            'suppliers' => Supplier::where('status', 1)->pluck('name', 'id'),
            'products' => Product::where('status', 1)->pluck('name', 'id'),
            'stores' => Store::where('status', 1)->pluck('name', 'id'),
            'payment_methods' => $this->paymentMethods()
        ];
        return view('backend.purchase.create', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required',
            'status' => 'required|in:0,1',
            'payment_mode' => 'required_if:paid_amount,>,0',
        ]);

        DB::transaction(function() use ($request) {
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            // 1. Create Purchase
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'store_id' => 1,
                'memo_number' => $request->memo_number,
                'purchase_date' => $date,
                'status' => $request->status,
                'discount' => $request->discount ?? 0,
                'tax' => 0,
                'sub_total' => 0,
                'grand_total' => 0,
                'paid_amount' => 0,
                'due_amount' => 0,
                'remarks' => $request->remarks,
            ]);

            // 2. Create Purchase Items
            if ($request->has('product_id')) {
                foreach ($request->product_id as $prodId) {
                    if (isset($request->quantity[$prodId])) {
                        foreach ($request->quantity[$prodId] as $index => $qty) {
                            $sku = $request->sku[$prodId][$index] ?? '';
                            $cost = $request->purchase_cost[$prodId][$index] ?? 0;
                            $salePrice = $request->sell_price[$prodId][$index] ?? 0;

                            $purchase->items()->create([
                                'product_id' => $prodId,
                                'sku' => $sku,
                                'ordered_qty' => $qty,
                                'received_qty' => $request->status == 1 ? $qty : 0,
                                'purchase_cost' => $cost,
                                'sale_price' => $salePrice,
                            ]);

                            // Update product variant / product cost and sale price
                            // And if status is Received (1), update stock!
                            $product = Product::find($prodId);
                            if ($product) {
                                if ($product->has_variant) {
                                    $variant = $product->variants()->where('variant_sku', $sku)->first();
                                    if ($variant) {
                                        $variant->update([
                                            'purchase_cost' => $cost,
                                            'variant_price' => $salePrice,
                                        ]);
                                        if ($request->status == 1) {
                                            $variant->increment('variant_stock', $qty);
                                        }
                                    }
                                } else {
                                    $product->update([
                                        'purchase_price' => $cost,
                                        'sale_price' => $salePrice,
                                    ]);
                                    if ($request->status == 1) {
                                        $product->increment('total_stock', $qty);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // 3. Create Payment if paid_amount > 0
            $paid = $request->paid_amount ?? 0;
            if ($paid > 0) {
                $purchase->payments()->create([
                    'amount' => $paid,
                    'payment_method' => $request->payment_mode,
                    'payment_date' => $date,
                ]);

                // Create supplier credit transaction (payment made to supplier)
                $purchase->supplier->transactions()->create([
                    'type' => 'credit',
                    'amount' => $paid,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'note' => 'Payment for Purchase #' . $purchase->id . ($request->note ? ' - ' . $request->note : ''),
                ]);
            }

            // Create supplier debit transaction for the purchase total
            $purchase->updateAmounts();

            $purchase->supplier->transactions()->create([
                'type' => 'debit',
                'amount' => $purchase->grand_total,
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'note' => 'Purchase #' . $purchase->id . ($request->remarks ? ' - ' . $request->remarks : ''),
            ]);
        });

        return redirect()->route('purchase.index')->with('success', 'Purchase created successfully.');
    }

    public function edit($id)
    {
        $purchase = Purchase::with(['items.product'])->findOrFail($id);
        $data = [
            'purchase' => $purchase,
            'suppliers' => Supplier::where('status', 1)->pluck('name', 'id'),
            'products' => Product::where('status', 1)->pluck('name', 'id'),
            'payment_methods' => [
                'Cash' => 'Cash',
                'Bank' => 'Bank',
                'Mobile Banking' => 'Mobile Banking'
            ]
        ];
        return view('backend.purchase.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required',
            'status' => 'required|in:0,1',
            'payment_mode' => 'required_if:paid_amount,>,0',
        ]);

        DB::transaction(function() use ($request) {
            $purchase = Purchase::findOrFail($request->purchase_id);
            $oldStatus = $purchase->status;
            $date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');

            // Revert stock changes if old status was Received (1) or Partial Receive (2)
            if ($oldStatus == 1 || $oldStatus == 2) {
                foreach ($purchase->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if ($product->has_variant) {
                            $variant = $product->variants()->where('variant_sku', $item->sku)->first();
                            if ($variant) {
                                $variant->decrement('variant_stock', $item->received_qty);
                            }
                        } else {
                            $product->decrement('total_stock', $item->received_qty);
                        }
                    }
                }
            }

            // Revert supplier transactions
            $purchase->supplier->transactions()->where('reference_type', Purchase::class)
                                              ->where('reference_id', $purchase->id)
                                              ->delete();

            // Delete existing payments and items
            $purchase->payments()->delete();
            $purchase->items()->delete();

            // Update basic fields
            $purchase->update([
                'supplier_id' => $request->supplier_id,
                'purchase_date' => $date,
                'status' => $request->status,
                'discount' => $request->discount ?? 0,
                'memo_number' => $request->memo_number,
                'remarks' => $request->remarks,
            ]);

            // Create new items
            if ($request->has('product_id')) {
                foreach ($request->product_id as $prodId) {
                    if (isset($request->quantity[$prodId])) {
                        foreach ($request->quantity[$prodId] as $index => $qty) {
                            $sku = $request->sku[$prodId][$index] ?? '';
                            $cost = $request->purchase_cost[$prodId][$index] ?? 0;
                            $salePrice = $request->sell_price[$prodId][$index] ?? 0;

                            $purchase->items()->create([
                                'product_id' => $prodId,
                                'sku' => $sku,
                                'ordered_qty' => $qty,
                                'received_qty' => $request->status == 1 ? $qty : 0,
                                'purchase_cost' => $cost,
                                'sale_price' => $salePrice,
                            ]);

                            $product = Product::find($prodId);
                            if ($product) {
                                if ($product->has_variant) {
                                    $variant = $product->variants()->where('variant_sku', $sku)->first();
                                    if ($variant) {
                                        $variant->update([
                                            'purchase_cost' => $cost,
                                            'variant_price' => $salePrice,
                                        ]);
                                        if ($request->status == 1) {
                                            $variant->increment('variant_stock', $qty);
                                        }
                                    }
                                } else {
                                    $product->update([
                                        'purchase_price' => $cost,
                                        'sale_price' => $salePrice,
                                    ]);
                                    if ($request->status == 1) {
                                        $product->increment('total_stock', $qty);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Create payments
            $paid = $request->paid_amount ?? 0;
            if ($paid > 0) {
                $purchase->payments()->create([
                    'amount' => $paid,
                    'payment_method' => $request->payment_mode,
                    'payment_date' => $date,
                ]);

                $purchase->supplier->transactions()->create([
                    'type' => 'credit',
                    'amount' => $paid,
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'note' => 'Payment for Purchase #' . $purchase->id . ($request->note ? ' - ' . $request->note : ''),
                ]);
            }

            $purchase->updateAmounts();

            // Supplier debit transaction
            $purchase->supplier->transactions()->create([
                'type' => 'debit',
                'amount' => $purchase->grand_total,
                'reference_type' => Purchase::class,
                'reference_id' => $purchase->id,
                'note' => 'Purchase #' . $purchase->id . ($request->remarks ? ' - ' . $request->remarks : ''),
            ]);
        });

        return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Request $request)
    {
        $purchase = Purchase::findOrFail($request->id);

        DB::transaction(function() use ($purchase) {
            // Revert stock changes if status was Received (1) or Partial Receive (2)
            if ($purchase->status == 1 || $purchase->status == 2) {
                foreach ($purchase->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        if ($product->has_variant) {
                            $variant = $product->variants()->where('variant_sku', $item->sku)->first();
                            if ($variant) {
                                $variant->decrement('variant_stock', $item->received_qty);
                            }
                        } else {
                            $product->decrement('total_stock', $item->received_qty);
                        }
                    }
                }
            }

            // Revert supplier transactions
            $purchase->supplier->transactions()->where('reference_type', Purchase::class)
                                              ->where('reference_id', $purchase->id)
                                              ->delete();

            $purchase->payments()->delete();
            $purchase->items()->delete();
            $purchase->delete();
        });

        return response()->json(['success' => true]);
    }

    public function getProductsAjax(Request $request)
    {
        $product = Product::with('variants.variantItems.attributeItem')->findOrFail($request->id);
        return view('backend.purchase.ajax_products', compact('product'));
    }

    public function getPurchaseItemsAjax(Request $request)
    {
        $data = Purchase::with(['purchase_items.get_product'])->findOrFail($request->id);
        $setting = Setting::first();
        return view('backend.purchase.view', compact('data', 'setting'));
    }

    // Purchase Receive lists & management
    public function receiveIndex()
    {
        $purchases = Purchase::with(['supplier', 'purchase_items'])->orderBy('id', 'desc')->paginate(50);
        $setting = Setting::first();
        return view('backend.purchase.recive-index', compact('purchases', 'setting'));
    }

    public function getPurchaseReceiveItemsAjax(Request $request)
    {
        $data = Purchase::with(['purchase_items.get_product'])->findOrFail($request->id);
        return view('backend.purchase.recive-view', compact('data'));
    }

    public function receiveAjax(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:purchase_items,id',
            'received' => 'required|integer|min:1',
        ]);

        return DB::transaction(function() use ($request) {
            $item = PurchaseItem::findOrFail($request->item_id);
            $qtyToReceive = $request->received;

            $remaining = $item->ordered_qty - $item->received_qty;
            if ($qtyToReceive > $remaining) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot receive more than remaining ordered quantity.'
                ]);
            }

            // Update received quantity
            $item->increment('received_qty', $qtyToReceive);

            // Update inventory stock
            $product = $item->product;
            if ($product) {
                if ($product->has_variant) {
                    $variant = $product->variants()->where('variant_sku', $item->sku)->first();
                    if ($variant) {
                        $variant->increment('variant_stock', $qtyToReceive);
                    }
                } else {
                    $product->increment('total_stock', $qtyToReceive);
                }
            }

            // Update status of the purchase itself
            $item->purchase->updateStatus();

            return response()->json([
                'success' => true,
                'new_received' => $item->received_qty
            ]);
        });
    }



    private function paymentMethods()
    {
        return [
            0 => 'Cash',
            1 => 'Card',
            2 => 'Mobile Banking',
            3 => 'Bank Cheque',
            4 => 'Bank Transfer',
            5 => 'Others'
        ];
    }
}
