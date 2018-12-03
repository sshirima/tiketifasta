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
             ->everyMinute()
             ->thenPing(config('scheduled-tasks.url_disable_merchants_after'))
             ->sendOutputTo('storage/logs/scheduled_tasks.log');//->dailyAt('00:00');,

         $schedule->command('buses-schedules')->everyMinute();//->dailyAt('00:00');

         $schedule->command('merchants:daily-payments')
             ->everyMinute()->thenPing(config('scheduled-tasks.url_pay_merchants_after'))
             ->sendOutputTo('storage/logs/scheduled_tasks_pay_merchants.log');//->dailyAt('00:00');
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
