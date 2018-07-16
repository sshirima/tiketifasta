<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/5/2018
 * Time: 11:00 PM
 */

namespace App\Services\BusesRoutes;


class BusRouteManager
{

    use TripManager;

    /**
     * @param $firstTime
     * @param $secondTime
     * @return bool
     */
    public static function compareTime($firstTime, $secondTime){
        $dateTime = new \DateTime($secondTime);
        if ($dateTime->diff(new \DateTime($firstTime))->format('%R') == '+') {
            return true;
        } else{
            return false;
        }
    }


    public static function addDays($date, $days){
        $endDate = new \DateTime($date);

        return $endDate->add(new \DateInterval('P'.$days.'D'));
    }
}