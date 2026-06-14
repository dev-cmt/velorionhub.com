<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use App\Models\CourierSetting;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourierController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    // Courier CRUD  (system_setting page section)
    // ─────────────────────────────────────────────────────────────

    /**
     * List all couriers (rendered as a partial inside system_setting.blade.php).
     * The full system_setting view fetches couriers via its own controller method,
     * so this index is kept for standalone/AJAX use if needed.
     */
    public function index()
    {
        $couriers = Courier::latest()->get();
        return view('backend.system_setting', compact('couriers'));
    }

    /** Store a new courier */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Courier::create([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Courier created successfully.');
    }

    /** Update courier name / slug */
    public function update(Request $request, Courier $courier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $courier->update([
            'name'   => $request->name,
            'slug'   => Str::slug($request->name),
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->back()->with('success', 'Courier updated successfully.');
    }

    /** Toggle courier active/inactive status (AJAX-friendly) */
    public function toggleStatus(Courier $courier)
    {
        $courier->update(['status' => !$courier->status]);

        if (request()->expectsJson()) {
            return response()->json(['status' => $courier->status]);
        }

        return redirect()->back()->with('success', 'Courier status updated.');
    }

    /** Delete a courier */
    public function destroy(Courier $courier)
    {
        $courier->delete();
        return redirect()->back()->with('success', 'Courier deleted successfully.');
    }

    // ─────────────────────────────────────────────────────────────
    // Courier Settings  (per-store credential management)
    // ─────────────────────────────────────────────────────────────

    /**
     * Show courier settings page for a given store.
     * Falls back to the first store if none selected.
     */
    public function settingsIndex(Request $request)
    {
        $stores  = Store::all();
        $storeId = $request->get('store_id', optional($stores->first())->id);

        $couriers = Courier::with(['settings' => function ($q) use ($storeId) {
            $q->where('store_id', $storeId);
        }])->get();

        // Attach a `setting` shortcut on each courier for the view
        $couriers->each(function ($courier) use ($storeId) {
            $courier->setting = $courier->settings->first();
        });

        return view('backend.courier_setting', compact('couriers', 'stores', 'storeId'));
    }

    /** Save / update courier credentials for a store */
    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'store_id'   => 'required|exists:stores,id',
        ]);

        $data = $request->only([
            'store_code', 'phone', 'email', 'password',
            'api_key', 'secret_key', 'client_id', 'client_secret', 'client_context',
        ]);

        $data['status'] = $request->has('status') ? 1 : 0;

        CourierSetting::updateOrCreate(
            [
                'store_id'   => $request->store_id,
                'courier_id' => $request->courier_id,
            ],
            $data
        );

        return redirect()->back()->with('success', 'Courier settings saved successfully.');
    }

    /** Generate OAuth tokens (Pathao example — extend per courier) */
    public function generateToken(Request $request)
    {
        $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'store_id'   => 'required|exists:stores,id',
        ]);

        $setting = CourierSetting::where('store_id', $request->store_id)
            ->where('courier_id', $request->courier_id)
            ->first();

        if (!$setting || !$setting->client_id || !$setting->client_secret) {
            return redirect()->back()->withErrors('Please save Client ID & Client Secret first.');
        }

        $courier = Courier::find($request->courier_id);

        if ($courier->slug === 'pathao') {
            $response = \Illuminate\Support\Facades\Http::post('https://api-hermes.pathao.com/aladdin/api/v1/issue-token', [
                'client_id'     => $setting->client_id,
                'client_secret' => $setting->client_secret,
                'username'      => $setting->email,
                'password'      => $setting->password,
                'grant_type'    => 'password',
            ]);

            if ($response->successful()) {
                $body = $response->json();
                $setting->update([
                    'access_token'  => $body['access_token']  ?? null,
                    'refresh_token' => $body['refresh_token'] ?? null,
                ]);
                return redirect()->back()->with('success', 'Pathao tokens generated successfully.');
            }

            return redirect()->back()->withErrors('Token generation failed: ' . $response->body());
        }

        return redirect()->back()->withErrors('Token generation not supported for this courier.');
    }
}
