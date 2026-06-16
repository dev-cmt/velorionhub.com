<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerStore;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Customer;
use Validator;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->paginate_value = 50;
    }

    public function index()
    {
        $customers = Customer::with('get_store')->orderBy('id', 'desc')->paginate($this->paginate_value);

        // dd($customers);
        // $customers = Customer::with('get_stores')->orderBy('id', 'desc')->paginate($this->paginate_value);
        $stores = DB::table('stores')->where('status', 1)->pluck('name', 'id');
        return view('backEnd.customer.index', compact('customers', 'stores'));
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
                $customer = Customer::create($request_all);

                foreach ($request_all['store'] as $key => $item) {
                    CustomerStore::create([
                        'store_id' => $item,
                        'customer_id' => $customer->id
                    ]);
                }
                return $customer;
            });
        }
    }

    public function update(Request $request)
    {
        // dd($request->all())
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'balance' => 'required',
            'status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['res_status' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $request_all = $request->all();
            DB::transaction(function () use ($request_all) {
                $customer = Customer::find($request_all['id'])->update($request_all);

                $customer_stores = CustomerStore::where('customer_id', $request_all['id'])->pluck('store_id')->toArray();
                //dd($customer_stores);
                if ($request_all['store']) {
                    //check if not in array and insert customer store
                    foreach ($request_all['store'] as $store) {
                        if (!in_array($store, $customer_stores)) {
                            CustomerStore::create([
                                'customer_id' => $request_all['id'],
                                'store_id' => $store,
                            ]);
                        }
                    }
                    //check if not in array and delete customer store
                    foreach ($customer_stores as $customer_store) {
                        if (!in_array($customer_store, $request_all['store'])) {
                            CustomerStore::where([['customer_id', $request_all['id']], ['store_id', $customer_store]])->delete();
                        }
                    }
                }
                return $customer;
            });
        }
    }

    public function delete(Request $request)
    {
        $customer = Customer::find($request->id)->delete();
        return $customer;
    }
}
