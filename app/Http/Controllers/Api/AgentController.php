<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AgentController extends Controller
{
    public function aiAgent(Request $request)
    {
        Log::info('Request Data: ', $request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data received successfully',
            'data' => $request->all()
        ], 200);
    }
}
