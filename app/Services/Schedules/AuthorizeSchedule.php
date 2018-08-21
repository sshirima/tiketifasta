<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Schedules;

use App\Models\Schedule;

trait AuthorizeSchedule
{

    /**
     * @param Schedule $schedule
     */
    public function enableSchedule(Schedule $schedule){
        $schedule->status = 1;
        $schedule->update();

    }

    /**
     * @param Schedule $schedule
     */
    public function disableSchedule(Schedule $schedule){
        $schedule->status = 0;
        $schedule->update();
    }
}