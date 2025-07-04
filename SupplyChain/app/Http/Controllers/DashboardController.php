<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function userRegistrations()
    {
        // Example: Get user registrations per month for the last 12 months
        $data = \App\Models\User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->take(12)
            ->get();

        return response()->json($data);
    }

}
