<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\Location;
use App\Models\Route;
use App\Repositories\BaseRepository;

class LocationRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Location::class;
    }

    public function getSelectLocationData($array){
        return $this->getLocationArray($array);
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getLocationArray($array){

        $locations = $this->all();

        foreach ($locations as $location) {
            $array[$location->id] = $location->name;
        }

        return $array;
    }
}