<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WeeklyReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;
use App\Models\Order;
use App\Models\User;
use App\Models\InventoryItem;
use Carbon\Carbon;

class ReportsController extends Controller
{
    // Show the reports dashboard
    public function index()
    {
        return view('admin.reports.index');
    }

    // Show the sales report page (real data)
    public function sales()
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subDays(7);
        $sales = Order::whereBetween('created_at', [$weekAgo, $now])->with('user')->get();
        $totalSales = $sales->sum('total_amount');
        $salesCount = $sales->count();
        return view('admin.reports.sales', compact('sales', 'totalSales', 'salesCount', 'weekAgo', 'now'));
    }

    // Show the user activity report page (real data)
    public function users()
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subDays(7);
        $newUsers = User::whereBetween('created_at', [$weekAgo, $now])->get();
        $activeUsers = User::whereHas('orders', function($q) use ($weekAgo, $now) {
            $q->whereBetween('created_at', [$weekAgo, $now]);
        })->get();
        $newUsersCount = $newUsers->count();
        $activeUsersCount = $activeUsers->count();
        return view('admin.reports.users', compact('newUsers', 'activeUsers', 'newUsersCount', 'activeUsersCount', 'weekAgo', 'now'));
    }

    // Show the inventory report page (real data)
    public function inventory()
    {
        $now = Carbon::now();
        $weekAgo = $now->copy()->subDays(7);
        $inventoryItems = InventoryItem::all();
        $lowStockItems = InventoryItem::where('quantity', '<', 10)->get();
        $totalInventory = InventoryItem::sum('quantity');
        return view('admin.reports.inventory', compact('inventoryItems', 'lowStockItems', 'totalInventory', 'weekAgo', 'now'));
    }

    // Handle export (HTML download for now)
    public function export(Request $request, WeeklyReportService $reportService)
    {
        $type = $request->input('report_type', 'sales');
        $data = $reportService->generateReportData();
        $html = $reportService->renderHtmlReport($data);
        $filename = 'weekly_report_' . now()->toDateString() . '.html';
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    // Manual trigger: send weekly report to logged-in admin
    public function sendToMe(WeeklyReportService $reportService)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }
        $data = $reportService->generateReportData();
        $html = $reportService->renderHtmlReport($data);
        Mail::to($user->email)->send(new WeeklyReportMail($html, 'Manual Weekly Admin Report: ' . now()->toDateString()));
        return back()->with('success', 'Weekly report sent to your email!');
    }
} 