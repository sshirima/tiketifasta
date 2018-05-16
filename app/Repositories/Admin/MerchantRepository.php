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
use App\Models\Merchant;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class MerchantRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Merchant::class;
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getSelectMerchantData($array){
        return $this->getMerchantArray($array);
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getMerchantArray($array){

        $merchants = $this->all();

        foreach ($merchants as $merchant) {
            $array[$merchant->id] = $merchant->name;
        }

        return $array;
    }
}