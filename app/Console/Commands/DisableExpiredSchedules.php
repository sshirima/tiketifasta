<?php

namespace App\Console\Commands;

use App\Models\Day;
use App\Services\Schedules\SchedulesAuthorization;
use Illuminate\Console\Command;

class DisableExpiredSchedules extends Command
{
    use SchedulesAuthorization;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buses-schedules:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable all expired schedules';


    /**
     * DisableExpiredSchedules constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Disable expired schedules
     */
    public function handle()
    {
        $days = Day::with(['schedules'])->whereDate('date' ,'<', date('Y-m-d'))->get();

        foreach ($days as $day){
            foreach ($day->schedules as $schedule){
                $this->deactivateSchedule($schedule, 0);
            }
        }
    }
}
