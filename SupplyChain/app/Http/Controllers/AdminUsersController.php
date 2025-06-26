<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminUsersController extends Controller
{
    public function index()
    {
        if(!Auth::check()){
            return redirect() ->route('welcome');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            abort(403, 'Access denied. Admin privileges required.');
        }

        $records = DB::select('SELECT * FROM pending_users');
                
        return view('admin.usersmanagement.index', [
            'pendingUsers' => json_encode($records),
        ]);
    }
}
