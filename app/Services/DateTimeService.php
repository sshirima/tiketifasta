<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/29/2018
 * Time: 4:25 PM
 */

namespace App\Services;


class DateTimeService
{
    /**
     * @param $date
     * @param $format
     * @return string
     */
    public static function convertDate($date, $format){
        $date = new \DateTime($date);
        return $date->format($format);
    }

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