<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    /**
     * Display a listing of warranties.
     */
    public function index()
    {
        $warranties = Warranty::latest()->paginate(10);
        return view('backend.inventory.warranties.index', compact('warranties'));
    }

    /**
     * Store a newly created warranty.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:warranties,name',
            'duration'    => 'required|integer|min:1',
            'period'      => 'required|in:day,month,year',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
        ]);

        Warranty::create($validated);

        return redirect()
            ->route('warranties.index')
            ->with('success', 'Warranty created successfully.');
    }

    /**
     * Update warranty.
     */
    public function update(Request $request)
    {
        $warranty = Warranty::findOrFail($request->id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:warranties,name,' . $warranty->id,
            'duration'    => 'required|integer|min:1',
            'period'      => 'required|in:day,month,year',
            'description' => 'nullable|string',
            'status'      => 'required|boolean',
        ]);

        $warranty->update($validated);

        return redirect()
            ->route('warranties.index')
            ->with('success', 'Warranty updated successfully.');
    }

    /**
     * Delete warranty.
     */
    public function destroy(Warranty $warranty)
    {
        $warranty->delete();

        return redirect()
            ->route('warranties.index')
            ->with('success', 'Warranty deleted successfully.');
    }
}
