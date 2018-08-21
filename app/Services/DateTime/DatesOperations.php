<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/16/2018
 * Time: 3:18 PM
 */

namespace App\Services\DateTime;


trait DatesOperations
{


    public function addDays($date, $days){
        $endDate = new \DateTime($date);

        return $endDate->add(new \DateInterval('P'.$days.'D'));
    }

    public function subDays($date, $days){
        $endDate = new \DateTime($date);

        return $endDate->sub(new \DateInterval('P'.$days.'D'));
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    public function convertDate($date, $format){
        $date = new \DateTime($date);
        return $date->format($format);
    }

    /**
     * @param $firstTime
     * @param $secondTime
     * @return bool
     */
    public function compareTime($firstTime, $secondTime){
        $dateTime = new \DateTime($secondTime);
        if ($dateTime->diff(new \DateTime($firstTime))->format('%R') == '+') {
            return true;
        } else{
            return false;
        }
    }
}