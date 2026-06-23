<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\AbandonedCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class SkipOrderController extends Controller
{
    //index method
    public function index()
    {
        $data = AbandonedCart::latest()->paginate(10);
        return view('backEnd.incomplete-orders.index', compact('data'));
    }

    public function createOrder(Request $request, $id)
    {
        DB::transaction(function () use ($id) {

            // 1️⃣ Find abandoned cart
            $abandoned = AbandonedCart::findOrFail($id);

            // dd($abandoned);
            // 2️⃣ Get related store info
            $store = DB::table('stores')->find($abandoned->store_id);

            // 3️⃣ Create/Get customer
            $customer = Customer::updateOrCreate(
                ['phone' => $abandoned->customer_phone],
                [
                    'name'    => $abandoned->customer_name,
                    'address' => $abandoned->customer_address,
                ]
            );

            // 4️⃣ Generate invoice number
            do {
                $day    = now()->format('d');
                $month  = now()->format('m');
                $random = strtoupper(Str::random(5)); // e.g., XDJE4

                $invoice_id = $store->invoice_prefix . $day . $month . $random;

            } while (Order::where('invoice_no', $invoice_id)->exists());

            // 5️⃣ Create Sale
            $sale = Order::create([
                'store_id'      => $abandoned->store_id,
                'customer_id'   => $customer->id,
                'invoice_no'    => $invoice_id,
                'customer_name' => $abandoned->customer_name,
                'customer_phone' => $abandoned->customer_phone,
                'customer_address'=> $abandoned->customer_address,
                'date'          => now(),
                'shipping_cost' => $abandoned->shipping_cost,
                'discount'      => $abandoned->discount,
                'sub_total'     => $abandoned->subtotal,
                'total'         => $abandoned->total,
                'paid'          => 0,
                'due'           => $abandoned->total ?? 0,
                'status'        => 0,
                'note'          => null,
                'source'        => 'incomplete',
            ]);

            // 6️⃣ Insert Sale Items
            $items = json_decode($abandoned->abandoned_item, true);
            foreach ($items as $item) {
                $variantAttrIds = [];
                if (!empty($item['attributes']) && is_array($item['attributes'])) {
                    foreach ($item['attributes'] as $attrName => $attrValue) {
                        // Create or get attribute
                        $attribute = Attribute::firstOrCreate(['name' => $attrName]);

                        // Create or get attribute item
                        $attributeItem = AttributeItem::firstOrCreate([
                            'attribute_id' => $attribute->id,
                            'name'         => $attrValue,
                        ]);

                        $variantAttrIds[] = (string) $attributeItem->id;
                    }
                }

                $product = DB::table('products')->find($item['product_id']);
                OrderItem::create([
                    'order_id'       => $sale->id,
                    'product_id'     => $item['product_id'],
                    'sku'            => $item['sku'],
                    'quantity'       => $item['qty'],
                    'item_out'       => $product->stock_out ?? 1,
                    'purchase_price' => $item['price'] * 0.75,  // $product->product_cost
                    'sale_price'     => $item['price'],         // $product->sale_price,
                    'attributes'     => json_encode($variantAttrIds),
                ]);
            }

            /* ---------- UPDATE Sales IMS ---------- */
            // $subTotal = DB::table('sale_items')->where('sale_id', $sale->id)->sum(DB::raw('unit_price * quantity'));
            // $shipping = $abandoned->shipping_cost ?? 0;
            // $discount = $abandoned->discount ?? 0;
            // $total = $subTotal + $shipping - $discount;

            // $sale->update([
            //     'sub_total' => $subTotal,
            //     'total'     => $total,
            //     'due'       => $total,
            // ]);

            // 7️⃣ Call API for saving order into the store
            if ($store && $store->base_url && $store->ep_order_create) {
                $url      = $store->base_url . $store->ep_order_create;
                $saleData = Order::with('sale_items')->find($sale->id);
                // dd($saleData);
                $response = api_call($url, 'POST', $saleData);
            }

            // 8️⃣ Remove the abandoned cart entry
            $abandoned->delete();

            // ----------------- ASSIGN EMPLOYEE -----------------
            if (Auth::user()->role_id == 4) {
                $sale->update(['assigned_to' => Auth::user()->id]);
            }else{
                $employees = User::where('role_id', 4)->where('status', 1)->get();

                $storeEmployees = $employees->where('store_id', $sale->get_store->id);
                if ($storeEmployees->count() > 0) {
                    $employee = $storeEmployees->random();
                }else{
                    $employee = $employees->count() > 0 ? $employees->random() : null;
                }
                if ($employee) {
                    $sale->update(['assigned_to' => $employee->id]);
                }
            }
        });

        return redirect()->back()->with('success', 'Order Created Successfully From Incomplete Order');
    }

    // delete abandoned cart
    public function delete($id)
    {
        $data = AbandonedCart::findOrFail($id);
        $data->delete();
        return redirect()->back()->with('success', 'Incompleted Order Deleted Successfully');
    }

    public function noteUpdate(Request $request)
    {
        AbandonedCart::find($request->id)->update([
            'note' => $request->note
        ]);
        return back()->with('success', 'Note Updated Successfully');
    }
}
