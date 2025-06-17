<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('supplier')) {
            return redirect()->route('supplier.dashboard');
        } elseif ($user->hasRole('manufacturer')) {
            return redirect()->route('manufacturer.dashboard');
        } elseif ($user->hasRole('wholesaler')) {
            return redirect()->route('wholesaler.dashboard');
        } else {
            return view('dashboard');
        }
    }
}
