<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserRoleController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.user_roles.index', compact('users'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.user-roles.index')->with('success', 'Role updated successfully.');
    }

    public function ajaxIndex()
    {
        $users = \App\Models\User::all();
        return view('admin.user_roles._table', compact('users'));
    }

    public function ajaxUpdate(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);
        $user->role = $request->role;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Role updated successfully.']);
    }
}
