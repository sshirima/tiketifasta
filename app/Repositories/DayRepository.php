<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 3:00 PM
 */

namespace App\Repositories;


use App\Models\Day;

class DayRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Day::class;
    }
}