<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;
use App\Http\Controllers\Traits\ManufacturerDashboardTrait;

class ManufacturerDashboardController extends Controller
{
    use ManufacturerDashboardTrait;

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        $stats = $this->getBasicStats();
        $recentActivities = $this->getRecentActivities();

        return view('manufacturer.dashboard', [
            'user' => $user,
            'stats' => $stats,
            'recentActivities' => $recentActivities
        ]);
    }

    public function markNotificationsAsRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return back();
    }
}
