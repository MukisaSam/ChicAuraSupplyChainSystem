<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    // List all users and their roles
    public function index()
    {
        $users = User::orderBy('name')->get();
        $roles = ['admin', 'manager', 'supplier', 'manufacturer', 'wholesaler']; // Define your roles here
        return view('admin.user_roles.index', compact('users', 'roles'));
    }

    // Update a user's role
    public function update(Request $request, $user)
    {
        $request->validate([
            'role' => 'required|string|in:admin,supplier,manufacturer,wholesaler'
        ]);

        $user = User::findOrFail($user);
        $user->role = $request->input('role');
        $user->save();

        return redirect()->route('admin.user_roles.index')->with('success', 'Role updated!');
    }
}
