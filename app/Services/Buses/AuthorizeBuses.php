<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Buses;


use App\Models\Bus;

trait AuthorizeBuses
{


    public function busEnable(Bus $bus){
        $bus->state = Bus::STATE_DEFAULT_ENABLED;
        $bus->update();
    }

    public function busDisable(Bus $bus){
        $bus->state = Bus::STATE_DEFAULT_DISABLED;
        $bus->update();
    }

    public function checkTripPrices(Bus $bus){
        $trips = $bus->trips;
        $status = true;

        if (count($trips) > 0){
            foreach ($trips as $trip){
                $status = isset($trip->price);
            }
        } else{
            $status = false;
        }

        $bus->trip_price_status = $status;

        return $bus;
    }
}