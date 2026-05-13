<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->where('is_admin', 0)->latest()->get();
        $roles = Role::all();
        return view('backend.users.employees', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            // 'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $user->photo_path = ImageHelper::uploadImage($request->file('photo'), 'uploads/profile');
        }

        $user->save();
        $user->assignRole('employee');

        return redirect()->back()->with('success', 'Employee created successfully.');
    }

    // Update
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            // 'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = User::findOrFail($request->id);

        if ($request->hasFile('photo')) {
            $user->photo_path = ImageHelper::uploadImage($request->file('photo'), 'uploads/profile', $user->photo_path);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        // $user->syncRoles([$request->role]);
        $user->assignRole('employee');

        return redirect()->back()->with('success', 'Employee updated successfully.');
    }

    // Delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        ImageHelper::deleteImage($user->photo_path);

        $user->delete();

        return redirect()->back()->with('success', 'Employee deleted successfully.');
    }
}
