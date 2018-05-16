<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/29/2018
 * Time: 8:14 PM
 */

namespace App\Repositories\User;

use App\Models\Booking;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Repositories\BaseRepository;

class BookingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'firstname', 'lastname', 'phonenumber'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Booking::class;
    }



}