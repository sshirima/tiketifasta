<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\Bustype;
use App\Models\Location;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class BusTypeRepository extends BaseRepository
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
        return Bustype::class;
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getSelectBusTypeData($array){
        return $this->getBusTypeArray($array);
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getBusTypeArray($array){

        $busTypes = $this->all();

        foreach ($busTypes as $busType) {
            $array[$busType->id] = $busType->name;
        }

        return $array;
    }
}