<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierWeeklyReportService;
use Illuminate\Support\Facades\Auth;

class SupplierReportsController extends Controller
{
    public function index(SupplierWeeklyReportService $reportService)
    {
        $supplier = Auth::user();
        if (!$supplier || $supplier->role !== 'supplier') {
            abort(403);
        }
        $data = $reportService->generateReportData($supplier);
        return view('supplier.reports.index', $data);
    }
} 