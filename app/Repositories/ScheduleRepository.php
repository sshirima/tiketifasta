<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 3:00 PM
 */

namespace App\Repositories;


use App\Models\Schedule;

class ScheduleRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Schedule::class;
    }
}