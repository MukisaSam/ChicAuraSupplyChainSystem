<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\WeeklyReportService;
use App\Mail\WeeklyReportMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWeeklyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:send-weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and send weekly reports to all admin users.';

    /**
     * Execute the console command.
     */
    public function handle(WeeklyReportService $reportService)
    {
        $admins = User::where('role', 'admin')->get();
        $data = $reportService->generateReportData();
        $html = $reportService->renderHtmlReport($data);
        $subject = 'Weekly Admin Report: ' . now()->toDateString();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new WeeklyReportMail($html, $subject));
                $this->info("Weekly report sent to {$admin->email}");
            } catch (\Exception $e) {
                Log::error("Failed to send weekly report to {$admin->email}: " . $e->getMessage());
                $this->error("Failed to send to {$admin->email}");
            }
        }
        $this->info('Weekly reports process completed.');
    }
} 
