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
use App\Models\Day;
use App\Models\Merchant;
use App\Models\Route;

trait BusRouteViewParams
{
    protected $routes = 'routes';
    protected $bus = 'bus';
    protected $tripsTable = 'tripTable';
    protected $travellingDays = 'dates';

    protected function getAssignRouteParams($bus, $table = null):array
    {
        $param = array($this->routes=>$this->getRoutesSelectArray(), $this->bus=>$bus);

        if (!$table == null){
            $param[$this->tripsTable] = $table;
        }
        return $param;
    }

    /**
     * @return string
     */
    public function getRoutes(): string
    {
        return $this->routes;
    }

    private function getRoutesSelectArray(){
        return Route::getRoutesSelectArray([__('admin_page_buses.select_routes_default')]);
    }

    private function getSchedulingDates(){
        return Day::getSchedulingDays(30);
    }

}