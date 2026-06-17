<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('get_store')->orderBy('id', 'desc')->paginate(10);
        $stores = DB::table('stores')->where('status', 1)->pluck('name', 'id');
        return view('backend.customers.index', compact('customers', 'stores'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'balance' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'store' => 'nullable|array',
            'store.*' => 'exists:stores,id'
        ]);

        if (!$validator->passes()) {
            return response()->json(['res_status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $request_all = $request->all();
        DB::transaction(function () use ($request_all) {
            $customer = Customer::create($request_all);

            if (isset($request_all['store']) && is_array($request_all['store'])) {
                foreach ($request_all['store'] as $store_id) {
                    CustomerStore::create([
                        'store_id' => $store_id,
                        'customer_id' => $customer->id
                    ]);
                }
            }
            return $customer;
        });
        return response()->json(['res_status' => 1, 'message' => 'Customer added successfully']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'balance' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
            'store' => 'nullable|array',
            'store.*' => 'exists:stores,id'
        ]);

        if (!$validator->passes()) {
            return response()->json(['res_status' => 0, 'error' => $validator->errors()->toArray()]);
        }

        $request_all = $request->all();
        DB::transaction(function () use ($request_all) {
            $customer = Customer::find($request_all['id']);
            if ($customer) {
                $customer->update($request_all);

                // Get current store assignments
                $customer_stores = CustomerStore::where('customer_id', $request_all['id'])->pluck('store_id')->toArray();
                $submitted_stores = $request_all['store'] ?? [];

                // Add new stores
                foreach ($submitted_stores as $store_id) {
                    if (!in_array($store_id, $customer_stores)) {
                        CustomerStore::create([
                            'customer_id' => $request_all['id'],
                            'store_id' => $store_id,
                        ]);
                    }
                }

                // Remove stores that are no longer assigned
                foreach ($customer_stores as $store_id) {
                    if (!in_array($store_id, $submitted_stores)) {
                        CustomerStore::where([
                            ['customer_id', $request_all['id']],
                            ['store_id', $store_id]
                        ])->delete();
                    }
                }
            }
            return $customer;
        });
        return response()->json(['res_status' => 1, 'message' => 'Customer updated successfully']);
    }

    public function destroy($id)
    {
        try {
            $customer = Customer::findOrFail($id);

            DB::transaction(function () use ($customer) {
                // Delete associated store relationships
                CustomerStore::where('customer_id', $customer->id)->delete();
                // Delete the customer
                $customer->delete();
            });

            return response()->json(['res_status' => 1, 'message' => 'Customer deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['res_status' => 0, 'message' => 'Failed to delete customer'], 500);
        }
    }
}
