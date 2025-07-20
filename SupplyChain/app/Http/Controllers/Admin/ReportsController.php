<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\WeeklyReportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;

class ReportsController extends Controller
{
    // Show the reports dashboard
    public function index()
    {
        return view('admin.reports.index');
    }

    // Show the sales report page (placeholder)
    public function sales()
    {
        return view('admin.reports.sales'); // Create this view or return a placeholder
    }

    // Show the user activity report page (placeholder)
    public function users()
    {
        return view('admin.reports.users'); // Create this view or return a placeholder
    }

    // Show the inventory report page (placeholder)
    public function inventory()
    {
        return view('admin.reports.inventory'); // Create this view or return a placeholder
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