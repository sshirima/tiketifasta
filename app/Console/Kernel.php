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
         $schedule->command('merchants:contract-disable')
             ->everyFiveMinutes();


         $schedule->command('buses-schedules')
              ->everyFiveMinutes();

         /*$schedule->command('merchants:daily-payments')
             ->everyFiveMinutes();*/

        $schedule->command('bookings:delete-pendings')
            ->everyFiveMinutes();
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
