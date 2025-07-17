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
    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|in:admin,manager,supplier,manufacturer,wholesaler', // Update as needed
        ]);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.user_roles.index')->with('success', 'User role updated!');
    }
}
