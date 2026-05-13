<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function indexManage()
    {
        $products = Product::with('category')->latest()->get();
        return view('backend.stock.index', compact('products'));
    }

    public function indexAdjustment()
    {
        $products = Product::latest()->get();
        return view('backend.stock.manage', compact('products'));
    }

    public function updateManage(Request $request, $id)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($id);
        
        if ($request->adjustment_type == 'add') {
            $product->total_stock += $request->quantity;
        } else {
            // Prevent negative stock
            if ($product->total_stock < $request->quantity) {
                return response()->json(['message' => 'Insufficient stock to subtract.'], 422);
            }
            $product->total_stock -= $request->quantity;
        }

        $product->save();

        return response()->json([
            'message' => 'Stock updated successfully!',
            'new_stock' => $product->total_stock
        ]);
    }

    public function updateAdjustment(Request $request, $id)
    {
        return $this->updateManage($request, $id);
    }


    public function indexTransfer()
    {
        $stores = Store::all();
        // Only show products that actually have stock to move
        $products = Product::where('total_stock', '>', 0)->get();
        
        $transfers = StockTransfer::with(['product', 'fromStore', 'toStore', 'requester'])
            ->latest()
            ->paginate(15);
        
        return view('backend.stock.transfer', compact('stores', 'products', 'transfers'));
    }

    /**
     * Create a new pending transfer request
     */
    public function storeTransfer(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'from_store_id' => 'required|exists:stores,id',
            'to_store_id'   => 'required|exists:stores,id|different:from_store_id',
            'quantity'      => 'required|integer|min:1',
            'note'          => 'nullable|string|max:255',
        ]);

        // Check if source has enough stock before even creating the request
        $product = Product::findOrFail($request->product_id);
        if ($product->total_stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available for transfer.');
        }

        StockTransfer::create([
            'product_id'    => $request->product_id,
            'from_store_id' => $request->from_store_id,
            'to_store_id'   => $request->to_store_id,
            'quantity'      => $request->quantity,
            'note'          => $request->note,
            'status'        => 'pending',
            'requested_by'  => Auth::id(),
        ]);

        return back()->with('success', 'Stock transfer request submitted for approval.');
    }

    /**
     * Approve or Reject the transfer
     */
    public function updateTransfer(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $transfer = StockTransfer::findOrFail($id);

        // Prevent double processing
        if ($transfer->status !== 'pending') {
            return response()->json(['message' => 'This transfer has already been processed.'], 422);
        }

        if ($request->status === 'approved') {
            return $this->processApproval($transfer);
        }

        // Handle Rejection
        $transfer->update(['status' => 'rejected']);
        return response()->json(['message' => 'Transfer request has been rejected.']);
    }
    private function processApproval($transfer)
    {
        return DB::transaction(function () use ($transfer) {
            $product = Product::lockForUpdate()->find($transfer->product_id);
            if ($product->total_stock < $transfer->quantity) {
                return response()->json(['message' => 'Insufficient stock to approve this transfer.'], 422);
            }
            $product->decrement('total_stock', $transfer->quantity);

            // 2. Mark transfer as approved
            $transfer->update([
                'status'      => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            return response()->json(['message' => 'Transfer approved. Inventory has been adjusted.']);
        });
    }
}
