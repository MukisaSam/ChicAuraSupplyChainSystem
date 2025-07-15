<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupplyRequest;
use App\Http\Controllers\Traits\ManufacturerDashboardTrait;
use App\Models\WorkOrder;
use App\Models\Supplier;
use App\Models\Wholesaler;

class ManufacturerDashboardController extends Controller
{
    use ManufacturerDashboardTrait;

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manufacturer') {
            abort(403, 'Access denied. Manufacturer privileges required.');
        }
        
        // Count of active work orders (not completed/cancelled)
        $activeWorkOrders = WorkOrder::whereNotIn('status', ['Completed', 'Cancelled'])->count();
        // Count of in-progress work orders
        $inProgress = WorkOrder::where('status', 'InProgress')->count();
        // Count of work orders completed this month
        $completedThisMonth = WorkOrder::where('status', 'Completed')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();
        // Recent work orders (latest 10)
        $workOrders = WorkOrder::with('product')->orderByDesc('created_at')->take(10)->get();
        // Add a progress attribute (dummy for now, can be improved)
        foreach ($workOrders as $order) {
            $order->progress = $order->status === 'Completed' ? 100 : ($order->status === 'InProgress' ? 50 : 0);
        }
        // Additional stats
        $totalRawMaterials = \App\Models\Item::where('type', 'raw_material')->count();
        $totalProducts = \App\Models\Item::where('type', 'finished_product')->count();
        $totalSuppliers = Supplier::count();
        $revenue = '$' . number_format(\App\Models\Order::where('status', 'delivered')->sum('total_amount'), 2);
        $recentActivities = $this->getRecentActivities();
        return view('manufacturer.dashboard', compact('activeWorkOrders', 'inProgress', 'completedThisMonth', 'workOrders', 'totalRawMaterials', 'totalProducts', 'totalSuppliers', 'revenue', 'recentActivities'));
    }

    public function markNotificationsAsRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();
        return back();
    }

    /**
     * Show the page to manage suppliers and wholesalers (message only).
     */
    public function managePartners()
    {
        $suppliers = Supplier::with('user')->get();
        $wholesalers = Wholesaler::with('user')->get();
        return view('manufacturer.manage-partners', compact('suppliers', 'wholesalers'));
    }
}
