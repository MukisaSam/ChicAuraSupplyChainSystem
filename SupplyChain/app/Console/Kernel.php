<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
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
        $schedule->command('reports:send-weekly')->weeklyOn(1, '8:00'); // Every Monday at 8am
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
