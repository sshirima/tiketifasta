<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories;

use App\Http\Presenters\StationPresenter;
use App\Models\Location;
use App\Models\Station;

class StationsRepository extends BaseRepository
{

    use StationPresenter;
    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Station::class;
    }

    public function createNewStation(array $attributes){
       return $this->create($attributes);
    }

    public function updateStation(array $attributes, $id){
        return $this->update($attributes, $id);
    }

    public function getDefaultStationTypes(){
        return Station::DEFAULT_STATIONS_TYPES;
    }

    public function getDefaultStationTypesNames(){
        return Station::DEFAULT_STATIONS_TYPES_NAMES;
    }

    public function getLocationsWithIds(){
        $location_array = [];
        $locations = Location::all();

        foreach ($locations as $location){
            $location_array[$location->id]=$location->name;
        }
        return $location_array;
    }

    public function findStationById($id){
        return $this->findWithoutFail($id);
    }


}