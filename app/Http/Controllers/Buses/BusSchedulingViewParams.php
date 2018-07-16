<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/8/2018
 * Time: 7:37 PM
 */

namespace App\Http\Controllers\Buses;


use App\Models\Day;
use App\Services\BusesRoutes\BusRouteManager;

trait BusSchedulingViewParams
{

    protected $bus = 'bus';
    protected $travellingDays = 'dates';
    protected $schedules = 'schedules';

    /**
     * @param $bus
     * @return array
     */
    function getCreateScheduleParams($bus){
        //Get events
        $events = $this->getBusSchedule($bus);

        return array($this->bus=>$bus,$this->travellingDays=>$this->getSchedulingDates(),$this->schedules=>$events);
    }

    /**
     * @param $bus
     * @return array
     */
    function getIndexScheduleParams($bus){
        //Get events
        $events = $this->getBusSchedule($bus);

        return array($this->bus=>$bus,$this->schedules=>$events);
    }

    /**
     * @return array
     */
    private function getSchedulingDates(){
        return Day::getSchedulingDays(30);
    }

    /**
     * @param $bus
     * @param string $direction
     * @return array
     */
    protected function getBusSchedule($bus, $direction ='GO'): array
    {
        if($direction == 'GO'){
            $schedules = $bus->schedules()->with(['day'])->going()->get();
        } else if ($direction == 'RETURN'){
            $schedules = $bus->schedules()->with(['day'])->returning()->get();
        } else{
            $schedules = array();
        }

        $events = $this->getScheduleEvents($bus, $schedules, $direction);

        return $events;
    }

    /**
     * @param $bus
     * @param $tr
     * @param $direction
     * @return mixed
     */
    protected function getTripDetails($bus, $tr, $direction)
    {
        if($direction == 'GO'){
            $trips = $bus->trips()->with(['to', 'from'])->going()->get();
        } else if ($direction == 'RETURN'){
            $trips = $bus->trips()->with(['to', 'from'])->returning()->get();
        } else{
            $trips = array();
        }

        foreach ($trips as $key => $trip) {
            if ($key == 0) {
                $tr['from'] = $trip->from->name;
                $tr['to'] = $trip->to->name;
                $tr['depart_time'] = $trip->depart_time;
                $tr['arrival_time'] = $trip->arrival_time;
            } else {

                if (BusRouteManager::compareTime($tr['depart_time'], $trip->depart_time)) {
                    $tr['depart_time'] = $trip->depart_time;
                    $tr['from'] = $trip->from->name;
                }

                if (BusRouteManager::compareTime($trip->arrival_time, $tr['arrival_time'])) {
                    $tr['arrival_time'] = $trip->arrival_time;
                    $tr['to'] = $trip->to->name;
                }
            }
        }
        return $tr;
    }

    /**
     * @param $bus
     * @param $schedules
     * @param $tr
     * @param $events
     * @return array
     */
    protected function createEvents($bus, $schedules, $tr, $events): array
    {

        foreach ($schedules as $key => $schedule) {
            $events[] = [

                'title' => $tr['from'] . ' - ' . $tr['to'],
                'start' => $schedule->day->date,
                'end' => BusRouteManager::addDays($schedule->day->date,$bus->route->travelling_days-1)->format('Y-m-d'),
                'depart_time' => $tr['depart_time'],
                'arrival_time' => $tr['arrival_time'],
                'duration' => $bus->route->travelling_days
            ];
        }
        return $events;
    }

    /**
     * @param $bus
     * @param $schedules
     * @param $direction
     * @return array
     */
    protected function getScheduleEvents($bus, $schedules, $direction): array
    {
        $events = array();

        $tr = array();

        $tr = $this->getTripDetails($bus, $tr, $direction);

        $events = $this->createEvents($bus, $schedules, $tr, $events);

        return $events;
    }
}