<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant;

use App\Models\Route;
use App\Models\Seat;
use App\Repositories\BaseRepository;

class SeatRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'seat_name'
    ];

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