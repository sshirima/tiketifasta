<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/16/2018
 * Time: 1:59 PM
 */

namespace App\Services\Trips;


use App\Services\DateTime\TimesOperations;

trait TripsAnalyser
{
    use TimesOperations;


    public function checkTripConsistent($trips, $direction){

        $intersect = array();
        $result = null;

        if (isset($trips)){
            if ($this->checkBusTripsCount($trips)){
                $intersect = $this->getIntersectTrip($trips, $direction);
            }
            if(array_key_exists('source',$intersect)){
                $result['source']['status'] = true;
                $result['source']['value'] = $intersect['source'];
                if (array_key_exists('depart_time',$intersect)){
                    $result['depart_time']['status'] = true;
                    $result['depart_time']['value'] = $intersect['depart_time'];
                } else {
                    $result['depart_time']['status'] = false;
                    $result['depart_time']['value'] = null;
                }
                return $result;
            }

            if(array_key_exists('destination',$intersect)){
                $result['destination']['status'] = true;
                $result['destination']['value'] = $intersect['destination'];
                if (array_key_exists('arrival_time',$intersect)){
                    $result['arrival_time']['status'] = true;
                    $result['arrival_time']['value'] = $intersect['arrival_time'];
                } else {
                    $result['arrival_time']['status'] = false;
                    $result['arrival_time']['value'] = null;
                }
                return $result;
            }
        }
        return $result;
    }

    /**
     * @param $trips
     * @param $direction
     * @return array
     */
    public function getIntersectTrip($trips, $direction):array{
        $master = array();
        $result_array = array();
        $i =0;
        if (count($trips) > 0 ){
            foreach ($trips as $key=>$trip){
                if($trip->direction == $direction){
                    if ($i == 0){
                        $master = $trip->toArray();
                        $result_array= $master;
                    } else {
                        $result_array = array_intersect_assoc($master, $trip->toArray());
                    }
                    $i++;
                }

            }
        }
        return $result_array;
    }

    /**
     * @param $trips
     * @return bool
     */
    public function checkBusTripsCount($trips){
        if (count($trips) % 2 == 0) {
            return true;
        } else {
            return false;
        }
    }
}