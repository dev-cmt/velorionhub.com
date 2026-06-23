<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data['total_applications'] = User::count();
        return view('backend.dashboard',compact('data'));
    }

    public function resyncPermissions()
    {
        // -------------------------------
        // 1️⃣ Define Modules
        // -------------------------------
        $modules = [
            'products',
            'categories',
            'brands',
            'tags',
            'units',
            'attributes',
            'warranties',
            'orders',
            'customers',
            'stores',
            'employees',
            'tickets',
            'blogs',
            'users',
            'roles',
        ];

        $singlePermissions = [
            'view dashboard',
            'view expired products',
            'view low stocks',
            'view label print',
            'view sale requisition',
            'view sale approve',

            'view stock manage',
            'view stock adjustments',
            'view stock transfers',

            'view parcel handling',
            'view return received',
            'view damages',

            'view sliders',
            'view banners',
            'view pages',
            'view seo',
            'view blogs',

            'view settings',
            'view settings website',
            'view settings system',
            'view settings financial',
            'view settings other',
            'view developer api',
            'comming soon',
        ];

        $allPermissions = [];

        // -------------------------------
        // 2️⃣ Create / Update Module Permissions
        // -------------------------------
        foreach ($modules as $module) {
            foreach (['view','create','edit','delete'] as $action) {
                $permissionName = "{$action} {$module}";
                Permission::updateOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
                $allPermissions[] = $permissionName;
            }
        }

        // -------------------------------
        // 3️⃣ Create / Update Single Permissions
        // -------------------------------
        foreach ($singlePermissions as $perm) {
            Permission::updateOrCreate(
                ['name' => $perm],
                ['guard_name' => 'web']
            );
            $allPermissions[] = $perm;
        }

        // -------------------------------
        // 4️⃣ Remove old permissions safely
        // -------------------------------
        $permissionsToRemove = Permission::whereNotIn('name', $allPermissions)->get();

        foreach ($permissionsToRemove as $permission) {
            // Remove from all roles
            foreach ($permission->roles as $role) {
                $role->revokePermissionTo($permission);
            }
            // Delete the permission
            $permission->delete();
        }

        dd('Permissions fully synced successfully!');
    }


    public function fraudCheck($id)
    {
        $order = Order::select('id', 'status', 'customer_phone', 'customer_activity')->find($id);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        $phone = preg_replace('/\D+/', '', (string) $order->customer_phone);

        if (str_starts_with($phone, '88') && strlen($phone) === 13) {
            $phone = substr($phone, 2);
        }

        if (strlen($phone) != 11) {
            return back()->with('warning', 'Phone number is not 11 digits');
        }

        // Call the API
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => env('FROODLY_URL') . '/api/check-courier',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                'phone' => $phone, // use actual phone
            ]),
            CURLOPT_HTTPHEADER => [
                'X-API-TOKEN: ' . env('FROODLY_TOKEN'),
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return back()->with('error', 'cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        $response = json_decode($response, true);
        if($response['status'] == false || $response === null){
            return back()->with('error', $response['message']);
        }

        $summaries = $response['data']['Summaries'] ?? [];

        // Prepare courier data safely
        $courierMap = [
            'steadfast' => ['SteadFast', 'steadfast'],
            'redx' => ['Redx', 'redx'],
            'pathao' => ['Pathao', 'pathao'],
            'paperfly' => ['Paperfly', 'paperfly'],
            'carrybee' => ['Carrybee', 'carrybee'],
        ];

        $data = [
            'total' => 0,
            'total_delivered' => 0,
            'total_returned' => 0,
        ];

        $summaryLookup = [];
        foreach ($summaries as $key => $value) {
            $summaryLookup[strtolower((string) $key)] = $value;
        }

        foreach ($courierMap as $key => $aliases) {
            $source = null;
            foreach ($aliases as $alias) {
                $lookupKey = strtolower($alias);
                if (isset($summaryLookup[$lookupKey])) {
                    $source = $summaryLookup[$lookupKey];
                    break;
                }
            }

            $total = (int) ($source['total'] ?? 0);
            $delivered = (int) ($source['success'] ?? 0);
            $returned = (int) ($source['cancel'] ?? 0);

            $data['total'] += $total;
            $data['total_delivered'] += $delivered;
            $data['total_returned'] += $returned;

            $data[$key] = [
                'delivered' => $delivered,
                'returned' => $returned
            ];
        }

        // Update order
        $order->update(['customer_activity' => json_encode($data)]);

        return back()->with('success', 'Activity Updated Successfully');
    }

}
