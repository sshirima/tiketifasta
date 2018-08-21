<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/8/2018
 * Time: 1:30 PM
 */

namespace App\Services\Schedules;


use App\Models\Bus;
use App\Models\Day;
use App\Services\DateTime\DatesOperations;
use App\Services\DateTimeService;

trait AssignScheduleService
{
    use DatesOperations;

    private $returnData = array();

    /**
     * @param Bus $bus
     * @param $date
     * @param $direction
     * @return array
     */
    public function assignSchedule(Bus $bus, $date, $direction){

        //Check if the bus has been assigned on that day
        $today = $this->formatDate($date);

        $routeDays = $bus->route()->first()->travelling_days;

        $yesterday = $this->subDays($today, $routeDays);

        $tomorrow = $this->addDays($today, $routeDays);

        $todaySchedule = $bus->scheduledDays()->where(['date'=>$today])->first();
        $yesterdaySchedule = $bus->scheduledDays()->where(['date'=>$yesterday])->first();
        $tomorrowSchedule = $bus->scheduledDays()->where(['date'=>$tomorrow])->first();

        if (isset($yesterdaySchedule)){
            $yesterdayDirection = $yesterdaySchedule->pivot->direction;

            if ($yesterdayDirection == $direction){
                $this->returnData['status'] = 0;
                $this->returnData['reason'] = 'Previous schedule direction conflicts';
            } else {
                $this->scheduleDate($bus, $direction, $today);
            }

        } else if (isset($tomorrowSchedule)){

            $tomorrowDirection = $tomorrowSchedule->pivot->direction;
            if ($tomorrowDirection == $direction){
                $this->returnData['status'] = 0;
                $this->returnData['reason'] = 'Next schedule direction conflicts';
            } else {
                $this->scheduleDate($bus, $direction, $today);
            }
        } else if (isset($todaySchedule)){

            $this->returnData['status'] = 0;
            $this->returnData['reason'] = 'Schedule already assigned';

        } else {
            $this->scheduleDate($bus, $direction, $today);
        }

        return $this->returnData;
    }

    private function getDefaultStatus(){
        return 1;
    }

    /**
     * @param $date
     * @return string
     */
    private function formatDate($date){
        return DateTimeService::convertDate($date,'Y-m-d' );
    }

    /**
     * @param Bus $bus
     * @param $direction
     * @param $today
     */
    protected function scheduleDate(Bus $bus, $direction, $today): void
    {
        //Create date
        $todaySchedule = Day::updateOrCreate(['date' => $today]);

        //Assign day to the bus
        $bus->scheduledDays()->attach($todaySchedule->id, ['direction' => $direction, 'status' => 1]);

        $this->returnData['status'] = 1;
        $this->returnData['reason'] = 'Schedule assigned successful';
    }
}