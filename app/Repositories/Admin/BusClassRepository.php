<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\BusClass;
use App\Repositories\BaseRepository;

class BusClassRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return BusClass::class;
    }

    public function getSelectBusClassData($array){
        return $this->getBusClassArray($array);
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getBusClassArray($array){

        $busClasses = $this->all();

        foreach ($busClasses as $location) {
            $array[$location->id] = $location[BusClass::COLUMN_CLASS_NAME];
        }

        return $array;
    }
}