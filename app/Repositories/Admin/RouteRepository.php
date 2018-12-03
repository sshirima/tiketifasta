<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Http\Controllers\Admins\RouteController;
use App\Models\Location;
use App\Models\Route;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelBootstrapTableList\TableList;

class RouteRepository extends BaseRepository
{
    public $conditions = array();
    public $entityColumns = array();

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Route::class;
    }

    /**
     * @param $array
     * @return mixed
     */
    public function getSelectRouteData($array){
        return $this->getRouteArray($array);
    }

    /**
     * @param $array
     * @return mixed
     */
    public static function getRouteArray($array){

        $routes = Route::all();

        foreach ($routes as $route) {
            $array[$route->id] = $route[Route::COLUMN_ROUTE_NAME];
        }

        return $array;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){
        //Set up the querying conditions here
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    /**
     * @return mixed
     */
    public function instantiateRouteTable()
    {
        $table = app(TableList::class)
            ->setModel(Route::class)
            ->setRoutes([
                'index' => ['alias' => RouteController::ROUTE_INDEX, 'parameters' => []],
                'create' => ['alias' => RouteController::ROUTE_CREATE, 'parameters' => []],
                'destroy' => ['alias' => RouteController::ROUTE_REMOVE, 'parameters' => []]])
            ->setRowsNumber(10)
            ->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(Location::TABLE.' as A', 'A.id', '=', Route::START_LOCATION)
                    ->join(Location::TABLE.' as B', 'B.id', '=', Route::END_LOCATION)
                    ->where($this->conditions);
            });
        return $table;
    }

    /**
     * @return array
     */
    public function travellingDaysArray(){
        return array(1=>'One',2=>'Two',3=>'Three',4=>'Four',5=>'Five');
    }

    /**
     * @return mixed
     */
    public function getAvailableRoutes(){
        $array= array();
        $routes = Route::with([Route::REL_LOCATIONS])->select([Route::COLUMN_ID,Route::COLUMN_ROUTE_NAME])->get();
        foreach ($routes as $route){
            $array[$route->id] = $route;
        }
        return $array;
    }
}