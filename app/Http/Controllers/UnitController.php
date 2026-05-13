<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of units.
     */
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('backend.inventory.units.index', compact('units'));
    }

    /**
     * Store a newly created unit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:units,name',
            'short_name' => 'required|string|max:20|unique:units,short_name',
            'unit_type'  => 'required|in:weight,volume,quantity',
            'status'     => 'required|boolean',
        ]);

        Unit::create($validated);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Update an existing unit.
     */
    public function update(Request $request)
    {
        $unit = Unit::findOrFail($request->id);

        $validated = $request->validate([
            'name'       => 'required|string|max:255|unique:units,name,' . $unit->id,
            'short_name' => 'required|string|max:20|unique:units,short_name,' . $unit->id,
            'unit_type'  => 'required|in:weight,volume,quantity',
            'status'     => 'required|boolean',
        ]);

        $unit->update($validated);

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Delete a unit.
     */
    public function destroy(Unit $unit)
    {
        // Optional: prevent delete if unit is used by products
        if ($unit->products()->exists()) {
            return redirect()
                ->route('units.index')
                ->with('error', 'This unit is already used by products.');
        }

        $unit->delete();

        return redirect()
            ->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}
