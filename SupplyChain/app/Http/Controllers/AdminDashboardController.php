<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
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

        // Stats focused on system administration
        $stats = [
            'total_users' => 68, // Manufacturers, Suppliers, Wholesalers
            'active_sessions' => 15,
            'system_status' => 'Operational',
            'pending_issues' => 2,
        ];

        // A log of recent administrative or system-wide activities
        $recentActivities = [
            ['icon' => 'fa-user-plus', 'color' => 'text-green-500', 'description' => 'New supplier "Global Textiles" registered.', 'time' => '15 mins ago'],
            ['icon' => 'fa-file-invoice', 'color' => 'text-blue-500', 'description' => 'Monthly sales report was generated.', 'time' => '1 hour ago'],
            ['icon' => 'fa-shield-alt', 'color' => 'text-yellow-500', 'description' => 'User "wholesaler_test" had 3 failed login attempts.', 'time' => '3 hours ago'],
            ['icon' => 'fa-cogs', 'color' => 'text-gray-500', 'description' => 'System backup completed successfully.', 'time' => '8 hours ago'],
        ];

        return view('admin.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function userRegistrationsAnalytics()
    {
        $data = DB::table('users')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }
}
