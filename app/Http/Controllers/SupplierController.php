<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->paginate(10);
        $stores = DB::table('stores')->where('status', 1)->pluck('name', 'id');
        return view('backend.supplier.index', compact('suppliers', 'stores'));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'balance' => 'required',
            'status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['res_status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $request_all = $request->all();
            DB::transaction(function () use ($request_all) {
                $supplier = Supplier::create($request_all);
                if (isset($request_all['balance']) && $request_all['balance'] != 0) {
                    $supplier->transactions()->create([
                        'type' => $request_all['balance'] > 0 ? 'debit' : 'credit',
                        'amount' => abs($request_all['balance']),
                        'note' => 'Initial Balance',
                    ]);
                }
                return $supplier;
            });
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
            'balance' => 'required',
            'status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['res_status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $request_all = $request->all();
            DB::transaction(function () use ($request_all) {
                $supplier = Supplier::find($request_all['id']);
                if ($supplier) {
                    $current_balance = $supplier->balance;
                    $supplier->update($request_all);

                    $new_balance = $request_all['balance'] ?? 0;
                    $diff = $new_balance - $current_balance;
                    if ($diff != 0) {
                        $supplier->transactions()->create([
                            'type' => $diff > 0 ? 'debit' : 'credit',
                            'amount' => abs($diff),
                            'note' => 'Balance Adjustment',
                        ]);
                    }
                }
                return $supplier;
            });
        }
    }

    public function delete(Request $request)
    {
        $supplier = Supplier::find($request->id)->delete();
        return $supplier;
    }


    public function supplierReport($supplierId)
    {
        $supplier = Supplier::with('transactions')->findOrFail($supplierId);

        // Optional: aggregate total purchases or dues
        $totalPurchases = $supplier->transactions->where('type', 'debit')->sum('amount');
        $totalPayments = $supplier->transactions->where('type', 'credit')->sum('amount');
        return view('backend.supplier.transactions-report', compact('supplier', 'totalPurchases', 'totalPayments'));
    }

    public function getBalanceAjax(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);
        return response()->json($supplier ? $supplier->balance : 0);
    }
}
