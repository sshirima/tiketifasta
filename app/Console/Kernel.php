<?php

namespace App\Console;

use App\Console\Commands\DisableExpiredMerchants;
use App\Console\Commands\DisableExpiredSchedules;
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
        DisableExpiredMerchants::class,
        DisableExpiredSchedules::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('merchants:daily-payments')
            ->withoutOverlapping()
            ->dailyAt('02:00');

         $schedule->command('merchants:contract-disable')
             ->dailyAt('02:00');

         $schedule->command('buses-schedules:disable')
              ->dailyAt('02:00');

        $schedule->command('bookings:delete-pendings')
            ->everyFiveMinutes();

        $schedule->command('bookings:expire')
            ->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
