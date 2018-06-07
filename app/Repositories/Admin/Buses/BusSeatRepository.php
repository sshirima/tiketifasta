<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin\Buses;

use App\Models\Route;
use App\Models\Seat;
use App\Repositories\BaseRepository;

class BusSeatRepository extends BaseRepository
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

}