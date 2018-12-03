<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 3:00 PM
 */

namespace App\Repositories;


use App\Http\Requests\Merchant\Buses\AssignRoutesBusRequest;
use App\Models\Bus;
use App\Models\Seat;

class SeatRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Seat::class;
    }

    /**
     * @param $bus
     */
    public function createBusSeats($bus){
        if ($bus->seats()->count() == 0){
            Seat::createBusSeats($bus[Bus::COLUMN_ID],$bus[Bus::COLUMN_BUSTYPE_ID]);
        }
    }
}