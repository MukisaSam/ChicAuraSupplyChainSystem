<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendWeeklyReports::class,
        Commands\SendAdminWeeklyReports::class,
        Commands\SendSupplierWeeklyReports::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('orders:automate-processing')->everyFiveMinutes();
        $schedule->command('notify:low-inventory-delayed-shipments')->everyFiveMinutes();
        $schedule->command('reports:send-weekly')->weekly();
        $schedule->command('reports:send-admin-weekly')->weekly();
        $schedule->command('reports:send-supplier-weekly')->weekly();
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        if (!is_dir(__DIR__.'/Commands')) {
            mkdir(__DIR__.'/Commands', 0777, true);
        }
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}