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

        $unreadCount = $user->unreadNotifications()->count();

        // Stats focused on system administration
        $totalUsers = \App\Models\User::count();
        $activeSessions = \App\Models\User::where('last_seen', '>=', now()->subMinutes(30))->count();
        //$pendingIssues = \App\Models\Issue::where('status', 'pending')->count();
        $adminCount = \App\Models\User::where('role', 'admin')->count();
        $supplierCount = \App\Models\User::where('role', 'supplier')->count();
        $manufacturerCount = \App\Models\User::where('role', 'manufacturer')->count();
        $wholesalerCount = \App\Models\User::where('role', 'wholesaler')->count();

        $stats = [
            'total_users' => $totalUsers,
            'active_sessions' => $activeSessions,
            'system_status' => 'Operational',
            //'pending_issues' => $pendingIssues,
            'admin_count' => $adminCount,
            'supplier_count' => $supplierCount,
            'manufacturer_count' => $manufacturerCount,
            'wholesaler_count' => $wholesalerCount,
        ];

        // Recent activities: fetch from AuditLog
        $recentActivities = \App\Models\AuditLog::with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function($log) {
                $icon = match($log->action) {
                    'login' => 'fa-sign-in-alt',
                    'logout' => 'fa-sign-out-alt',
                    'create' => 'fa-plus-circle',
                    'update' => 'fa-edit',
                    'delete' => 'fa-trash',
                    default => 'fa-info-circle',
                };
                $color = match($log->action) {
                    'login' => 'text-green-500',
                    'logout' => 'text-gray-500',
                    'create' => 'text-blue-500',
                    'update' => 'text-yellow-500',
                    'delete' => 'text-red-500',
                    default => 'text-gray-500',
                };
                return [
                    'icon' => $icon,
                    'color' => $color,
                    'description' => $log->details,
                    'time' => $log->created_at ? $log->created_at->diffForHumans() : '',
                    'user' => $log->user->name ?? 'System',
                ];
            });

        return view('admin.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentActivities' => $recentActivities,
            'unreadCount' => $unreadCount,
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
