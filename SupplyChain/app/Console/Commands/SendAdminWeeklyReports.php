<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\WeeklyReportMail;
use App\Models\Order;
use App\Models\User;

class SendAdminWeeklyReport extends Command
{
    protected $signature = 'report:weekly';
    protected $description = 'Send weekly report to admin';

    public function handle()
    {

$reportData = [
    'newUsersCount' => User::where('created_at', '>=', now()->subWeek())->count(),

    'activeUsersCount' => Order::where('created_at', '>=', now()->subWeek())
        ->distinct('user_id')->count('user_id'),

    'newUsers' => User::where('created_at', '>=', now()->subWeek())
        ->select('name', 'email', 'created_at')
        ->orderBy('created_at', 'desc')
        ->get(),

    'activeUsers' => User::whereIn('id', Order::where('created_at', '>=', now()->subWeek())
        ->pluck('user_id')->unique())
        ->withCount(['orders' => function ($q) {
            $q->where('created_at', '>=', now()->subWeek());
        }])
        ->orderByDesc('orders_count')
        ->get(),
];


        // Fetch admin email
        $adminEmail = config('mail.admin_address', 'admin@example.com');
       $admin = \App\Models\User::where('role', 'admin')->first();

        Mail::to($admin->email)->send(new WeeklyReportMail($reportData, $admin, 'summary'));

        $this->info('Weekly report sent to admin.');
    }
}
