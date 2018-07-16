<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/2/2018
 * Time: 3:08 PM
 */

namespace App\Http\Controllers\Buses;


use App\Models\Bus;
use App\Models\Bustype;
use App\Models\Merchant;

trait BusViewParams
{
    protected $busTypes = 'busTypes';
    protected $merchants = 'merchants';
    protected $bus = 'bus';
    protected $conditions = 'conditions';

    private function getEditParams($bus){
        $editParam = $this->getCreateParams();
        $editParam[$this->bus] = $bus;
        $editParam[$this->conditions] = $this->getBusConditionArray();
        return $editParam;
    }

    private function getBusConditionArray(){
        return array(
            Bus::CONDITION_DEFAULT_OPERATIONAL=>Bus::CONDITION_DEFAULT_OPERATIONAL,
            Bus::CONDITION_DEFAULT_MAINTANANCE=>Bus::CONDITION_DEFAULT_MAINTANANCE,
            Bus::CONDITION_DEFAULT_ACCIDENT=>Bus::CONDITION_DEFAULT_ACCIDENT,
        );
    }
    private function getCreateParams():array
    {
        return array($this->busTypes=>$this->getBusTypeSelectArray(),
            $this->merchants=>$this->getMerchantSelectArray(),
            $this->conditions =>$this->getBusConditionArray()
        );
    }

    private function getMerchantSelectArray(){
        return Merchant::getMerchantSelectArray([__('admin_page_buses.select_merchant_default')]);
    }

    private function getBusTypeSelectArray(){
        return Bustype::getBusTypeSelectArray([__('admin_page_buses.select_bus_type_default')]);
    }

}