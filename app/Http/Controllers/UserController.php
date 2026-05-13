<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class UserController extends Controller
{
    // List users
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all()->whereNotIn('name', ['client']);
        return view('backend.users.index', compact('users', 'roles'));
    }

    // Create new User
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $user->photo_path = ImageHelper::uploadImage($request->file('photo'), 'uploads/profile');
        }

        $user->is_admin = $request->is_admin ?? 1;
        $user->save();
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'User created successfully.');
    }

    // Update user
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = User::findOrFail($request->id);

        if ($request->hasFile('photo')) {
            $user->photo_path = ImageHelper::uploadImage($request->file('photo'), 'uploads/profile', $user->photo_path);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->is_admin ?? 1;
        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // Delete User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        ImageHelper::deleteImage($user->photo_path);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
