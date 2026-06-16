<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function pathaoWebhook()
    {
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die(header('HTTP/1.0 415 Unsupported Media Type'));
        }

        //for webhook integration
        if ($object['event'] == 'webhook_integration') {
            $data = [
                'status' => 'accepted',
                'message' => 'Webhook received successfully'
            ];
            return response()->json($data, 202)->header('X-Pathao-Merchant-Webhook-Integration-Secret', 'f3992ecc-59da-4cbe-a049-a13da2018d51');
        }

        //file_put_contents(base_path('callback.txt'), $object);

        $pathao_store_id = DB::table('stores')->where('pathao_store_id', $object['store_id'])->select('id', 'pathao_store_id', 'status', 'ep_order_status', 'base_url')->first();
        if ($pathao_store_id && $pathao_store_id->pathao_store_id) { //if found store id
            if ($object['event'] == 'order.created') {
                DB::table('sales')->where([['invoice_no', $object['merchant_order_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => 'Order Created',
                    'consignment_id' => $object['consignment_id'],
                    //'status' => 7,
                    'courier_api_response' => null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                if ($pathao_store_id->status == 1) {
                    $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=7' . '&invoice_no=' . $object['merchant_order_id'];
                    //dd($url);
                    $response = api_call($url, 'GET', null);
                    //dd($response);
                }

            } elseif ($object['event'] == 'order.updated') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-requested') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.assigned-for-pickup') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.picked') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-failed') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-cancelled') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.at-the-sorting-hub') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.in-transit') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.received-at-last-mile-hub') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.assigned-for-delivery') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.delivered') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'status' => 9,
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($pathao_store_id->status == 1) {
                //     $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=9' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.partial-delivery') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'status' => 10,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($pathao_store_id->status == 1) {
                //     $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=10' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.returned') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'status' => 11,
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($pathao_store_id->status == 1) {
                //     $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=11' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.delivery-failed') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.on-hold') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.paid') {
                $order_id = DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => $object['invoice_id'],
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                    // 'payment_status' => 2,
                ]);
            } elseif ($object['event'] == 'order.paid-return') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'status' => 11,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($pathao_store_id->status == 1) {
                //     $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=11' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.exchanged') {
                DB::table('sales')->where([['consignment_id', $object['consignment_id']], ['store_id', $pathao_store_id->id]])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'status' => 13,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($pathao_store_id->status == 1) {
                //     $url = $pathao_store_id->base_url . $pathao_store_id->ep_order_status . '?status=13' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            }
        }
        return response()->json()->header('X-Pathao-Merchant-Webhook-Integration-Secret', 'f3992ecc-59da-4cbe-a049-a13da2018d51');
    }

    public function steadfastWebhook()
    {
        $json = file_get_contents('php://input');
        $object = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die(header('HTTP/1.0 415 Unsupported Media Type'));
        }

        if ($object['notification_type'] == 'delivery_status') {//update sale statuses
            $sale = Order::where([['consignment_id', $object['consignment_id']], ['invoice_no', $object['invoice']]])->first();
            if ($sale) {
                $store = DB::table('stores')->where('id', $sale->store_id)->select('id', 'base_url', 'ep_order_status', 'status', 'is_steadfast_active')->first();
                if ($object['status'] == 'delivered') {
                    $sale->update([
                        'courier_status' => array_key_exists('status', $object) ? $object['status'] : null,
                        'status' => 9,
                        'courier_status_reason' => array_key_exists('tracking_message', $object) ?? $object['tracking_message'],
                        'total_delivery_fee' => array_key_exists('delivery_charge', $object) ?? $object['delivery_charge'],
                    ]);

                    //call api for update data into particular store
                    // if ($store->status == 1) {
                    //     $url = $store->base_url . $store->ep_order_status . '?status=9' . '&invoice_no=' . $object['invoice'];
                    //     //dd($url);
                    //     $response = api_call($url, 'GET', null);
                    //     //dd($response);
                    // }
                } elseif ($object['status'] == 'partial_delivered') {
                    $sale->update([
                        'courier_status' => array_key_exists('status', $object) ? $object['status'] : null,
                        'status' => 10,
                        'courier_status_reason' => array_key_exists('tracking_message', $object) ?? $object['tracking_message'],
                        'total_delivery_fee' => array_key_exists('delivery_charge', $object) ?? $object['delivery_charge'],
                    ]);

                    //call api for update data into particular store
                    // if ($store->status == 1) {
                    //     $url = $store->base_url . $store->ep_order_status . '?status=10' . '&invoice_no=' . $object['invoice'];
                    //     //dd($url);
                    //     $response = api_call($url, 'GET', null);
                    //     //dd($response);
                    // }
                } elseif ($object['status'] == 'cancelled') {
                    $sale->update([
                        'courier_status' => array_key_exists('status', $object) ? $object['status'] : null,
                        'status' => 11,
                        'courier_status_reason' => array_key_exists('tracking_message', $object) ?? $object['tracking_message'],
                        'total_delivery_fee' => array_key_exists('delivery_charge', $object) ?? $object['delivery_charge'],
                    ]);

                    //call api for update data into particular store
                    // if ($store->status == 1) {
                    //     $url = $store->base_url . $store->ep_order_status . '?status=11' . '&invoice_no=' . $object['invoice'];
                    //     //dd($url);
                    //     $response = api_call($url, 'GET', null);
                    //     //dd($response);
                    // }
                }
            }

        }


        if ($object['notification_type'] == 'tracking_update') {//parcel tracking status update
            $sale = Order::where([['consignment_id', $object['consignment_id']], ['invoice_no', $object['invoice']]])->first();
            if ($sale) {
                $sale->update([
                    'courier_status' => array_key_exists('tracking_message', $object) ? $object['tracking_message'] : null,
                ]);
            }

        }

    }

    public function carryBeeWebhook(Request $request)
    {
        $json = file_get_contents('php://input');
        //file_put_contents(base_path('callback.txt'), $json);
        $object = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            die(header('HTTP/1.0 415 Unsupported Media Type'));
        }

        //for webhook integration
        if ($object['event'] == 'webhook.integration') {
            $data = [
                'status' => 'accepted',
                'message' => 'Webhook received successfully'
            ];
            return response()->json($data, 202)->header('X-CB-Webhook-Integration-Header', '40489fe0-9386-4fc9-8e92-2b2fcb9d451c');
        }

        $sale = Order::where([['consignment_id', $object['consignment_id']], ['invoice_no', $object['merchant_order_id']]])->first();
        if ($sale) {
            $store = DB::table('stores')->where('id', $sale->store_id)->select('id', 'base_url', 'ep_order_status', 'status', 'carrybee_is_active')->first();

            if ($object['event'] == 'order.updated') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-requested') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.assigned-for-pickup') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.picked') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-failed') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.pickup-cancelled') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.at-the-sorting-hub') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.on-the-way-to-central-warehouse') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.at-central-warehouse') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.in-transit') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.received-at-last-mile-hub') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.assigned-for-delivery') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.delivery-on-hold') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.delivered') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'status' => 9,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($store->status == 1) {
                //     $url = $store->base_url . $store->ep_order_status . '?status=9' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.partial-delivery') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'status' => 10,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                //call api for update data into particular store
                // if ($store->status == 1) {
                //     $url = $store->base_url . $store->ep_order_status . '?status=10' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.delivery-failed') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.returned') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'status' => 11,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                // if ($store->status == 1) {
                //     $url = $store->base_url . $store->ep_order_status . '?status=11' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.paid-return') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'status' => 11,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                // if ($store->status == 1) {
                //     $url = $store->base_url . $store->ep_order_status . '?status=11' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.exchange') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'status' => 13,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);

                // if ($store->status == 1) {
                //     $url = $store->base_url . $store->ep_order_status . '?status=13' . '&invoice_no=' . $object['merchant_order_id'];
                //     //dd($url);
                //     $response = api_call($url, 'GET', null);
                //     //dd($response);
                // }
            } elseif ($object['event'] == 'order.paid') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.returned-at-sorting') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            } elseif ($object['event'] == 'order.returned-to-merchant') {
                DB::table('sales')->where('consignment_id', $object['consignment_id'])->update([
                    'courier_status' => array_key_exists('order_status', $object) ? $object['order_status'] : null,
                    'courier_status_reason' => array_key_exists('reason', $object) ? $object['reason'] : null,
                    'total_delivery_fee' => array_key_exists('delivery_fee', $object) ?? $object['delivery_fee'],
                ]);
            }
        }

        //62gb0TjPkKaNsbF9MNWZoR7
        return response()->json()
            ->header('X-BEE-Signature', 'qts6wiriqasgd')
            ->header('Accept', 'application/json')
            ->header('Content-Type', 'application/json')
            ->header('Content-Length', 185);
    }

}
