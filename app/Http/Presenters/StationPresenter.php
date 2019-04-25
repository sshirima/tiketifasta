<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 3/7/2019
 * Time: 11:31 PM
 */

namespace App\Http\Presenters;

trait StationPresenter
{
    /**
     * @return mixed
     */
    public function getStationsTable(){
        $tableCreator = new StationsTable($this->model());
        return $tableCreator->getTable();
    }

    public function getCreateNewStationPreInputs(){
        $station_types = [0=>'Select station types']+$this->getDefaultStationTypesNames();
        $locations = [0=>'Select locations']+$this->getLocationsWithIds();
        return ['station_types'=>$station_types,'locations'=>$locations];
    }

    public function getEditStationInputs($id){
        $station_types = [0=>'Select station types']+$this->getDefaultStationTypesNames();
        $locations = [0=>'Select locations']+$this->getLocationsWithIds();
        $station = $this->findStationById($id);

        return ['station_types'=>$station_types,'locations'=>$locations,'station'=>$station];
    }
}