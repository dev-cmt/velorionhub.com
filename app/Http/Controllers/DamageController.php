<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Damage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DamageController extends Controller
{
    protected $paginate = 50;

    // Show damage list
    public function index()
    {
        $damages = Damage::with('get_product')->orderByDesc('id')->paginate($this->paginate);
        $products = Product::select('id', 'name', 'sku', 'total_stock')->get();
        return view('backend.damage.index', compact('damages', 'products'));
    }

    // Store new damage (Standard Form Submit)
    public function store(Request $request)
    {
        $request->validate([
            'date'       => 'required|date_format:d-m-Y',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'sku'        => 'nullable|string',
        ]);

        $damage = new Damage();
        $damage->date       = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $damage->product_id = $request->product_id;
        $damage->sku        = $request->sku;
        $damage->quantity   = $request->quantity;
        $damage->note       = $request->note;
        // Logic check: if you want to calculate amount based on product price, do it here
        $damage->amount     = $request->amount ?? 0;
        $damage->save();

        return redirect()->back()->with('success', 'Damage added successfully');
    }

    // Update damage (Standard Form Submit)
    public function update(Request $request)
    {
        $request->validate([
            'id'         => 'required|exists:damages,id',
            'date'       => 'required|date_format:d-m-Y',
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'sku'        => 'nullable|string',
        ]);

        $damage = Damage::findOrFail($request->id);
        $damage->date       = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
        $damage->product_id = $request->product_id;
        $damage->sku        = $request->sku;
        $damage->quantity   = $request->quantity;
        $damage->note       = $request->note;
        $damage->amount     = $request->amount ?? $damage->amount;
        $damage->save();

        return redirect()->back()->with('success', 'Damage updated successfully');
    }

    // Delete damage (Redirecting back)
    public function delete (Request $request)
    {
        // Using $request->id because your JS sends window.location.href + ?id=
        $id = $request->id;
        $damage = Damage::findOrFail($id);
        $damage->delete();

        return redirect()->back()->with('success', 'Damage deleted successfully');
    }

    // AJAX: fetch damage data for the modal (Still needed for the Edit button)
    public function getDamageData(Request $request)
    {
        $damage = Damage::findOrFail($request->id);
        // Format date for flatpickr back to d-m-Y
        $damage->date = Carbon::parse($damage->date)->format('d-m-Y');
        return response()->json($damage);
    }
}
