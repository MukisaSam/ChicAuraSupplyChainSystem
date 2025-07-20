<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupplierWeeklyReportService;
use App\Mail\SupplierWeeklyReportMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSupplierWeeklyReports extends Command
{
    protected $signature = 'reports:send-supplier-weekly';
    protected $description = 'Generate and send weekly reports to all suppliers.';

    public function handle(SupplierWeeklyReportService $reportService)
    {
        $suppliers = User::where('role', 'supplier')->get();
        foreach ($suppliers as $supplier) {
            try {
                $data = $reportService->generateReportData($supplier);
                $html = $reportService->renderHtmlReport($data);
                $subject = 'Your Weekly Supplier Report: ' . now()->toDateString();
                Mail::to($supplier->email)->send(new SupplierWeeklyReportMail($html, $supplier, $subject));
                $this->info("Weekly report sent to {$supplier->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send weekly report to {$supplier->email}: " . $e->getMessage());
                $this->error("Failed to send to {$supplier->email}");
            }
        }
        $this->info('Supplier weekly reports process completed.');
    }
} 