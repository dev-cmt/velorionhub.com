<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class AiAgentController extends Controller
{
    public function aiAgent(Request $request)
    {
        Log::info('Request Data: ', $request->all());

        if (!$request->has('transaction_id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction ID is required'
            ], 400);
        }

        // Process the order action
        if ($request->input('order_action') == 'Confirmed') {
            Order::where('invoice_no', $request->input('transaction_id'))->update(['status' => 1]);
        }elseif ($request->input('order_action') == 'Cancelled') {
            Order::where('invoice_no', $request->input('transaction_id'))->update(['status' => 3 ]);
        }else {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid order action'
            ], 400);
        }

        // Return from the AI Agent
        $data = [
            'transaction_id' => $request->input('transaction_id'),
            'order_action' => $request->input('order_action'),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $data
        ], 200);
    }
}
