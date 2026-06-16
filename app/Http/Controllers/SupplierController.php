<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierStore;
use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Validator;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->paginate_value = 50;
    }

    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->paginate($this->paginate_value);
        $stores = DB::table('stores')->where('status', 1)->pluck('name', 'id');
        return view('backEnd.supplier.index', compact('suppliers', 'stores'));
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
                $supplier = Supplier::find($request_all['id'])->update($request_all);
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
        return view('backEnd.supplier.transactions-report', compact('supplier', 'totalPurchases', 'totalPayments'));
    }
}
