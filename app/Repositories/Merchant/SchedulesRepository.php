<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 2:34 PM
 */

namespace App\Repositories\Merchant;


use App\Models\Schedule;
use App\Repositories\BaseRepository;

class SchedulesRepository extends BaseRepository
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