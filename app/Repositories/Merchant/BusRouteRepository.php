<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/13/2018
 * Time: 7:15 PM
 */

namespace App\Repositories\Merchant;


use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Day;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BusRouteRepository extends BaseRepository
{
    public $conditions = array();
    public $entityColumns = array();


    /**
     * @var array
     */
    protected $fieldSearchable = [
        'bus_id'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return BusRoute::class;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){
        //Set up the querying conditions here
        if ($request->filled('date')) {
            $this->conditions[Day::DATE] = $request['date'];
        }

        if ($request->filled('route_id')) {
            if ($request['route_id'] > 0) {
                $this->conditions[Route::ID] = $request['route_id'];
            }
        }

        if ($request->filled('bus_route_status')) {
            if ($request['bus_route_status'] ==1 || $request['bus_route_status'] ==0) {
                $this->conditions['bus_route.status'] = $request['bus_route_status'];
            }
        }

        if ($request->filled('merchant_id')) {
            if ($request['merchant_id'] > 0) {
                $this->conditions['merchants.id'] = $request['merchant_id'];
            }
        }
        if ($request->filled('bus_id')) {
            if ($request['bus_id'] > 0) {
                $this->conditions['bus_route.bus_id'] = $request['bus_id'];
            }
        }

        if ($request->filled('bus_route_id')) {
            if ($request['bus_route_id'] > 0) {
                $this->conditions['bus_route.id'] = $request['bus_route_id'];
            }
        }
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
    public function instantiateSchedulesTable()
    {
        $table = app(TableList::class)
            ->setModel(Day::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(Schedule::TABLE, Schedule::DAY_ID, '=', Day::ID)
                    ->join(BusRoute::TABLE, BusRoute::ID, '=', Schedule::BUS_ROUTE_ID)
                    ->join(Bus::TABLE,  BusRoute::BUS_ID, '=',Bus::ID)
                    ->join(Merchant::TABLE,  Merchant::ID, '=',Bus::MERCHANT_ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(SubRoute::TABLE, SubRoute::BUS_ROUTE_ID, '=', BusRoute::ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });
        return $table;
    }

    /**
     * @return mixed
     */
    public function instantiateBusRoutes()
    {
        $table = app(TableList::class)
            ->setModel(BusRoute::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(Bus::TABLE,  BusRoute::BUS_ID, '=',Bus::ID)
                    ->join(Merchant::TABLE,  Merchant::ID, '=',Bus::MERCHANT_ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(SubRoute::TABLE, SubRoute::BUS_ROUTE_ID, '=', BusRoute::ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });
        return $table;
    }

    /**
     * @return mixed
     */
    public function instantiateRoutes()
    {
        $table = app(TableList::class)
            ->setModel(BusRoute::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(Bus::TABLE,  BusRoute::BUS_ID, '=',Bus::ID)
                    ->join(Merchant::TABLE,  Merchant::ID, '=',Bus::MERCHANT_ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', Route::START_LOCATION)
                    ->join(Location::TABLE.' as B', 'B.id', '=', Route::END_LOCATION)
                    ->where($this->conditions);
            });
        return $table;
    }


    /**
     * @return mixed
     */
    public function busRouteTableData()
    {
        $table = app(TableList::class)
            ->setModel(BusRoute::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select(['bus_route.status as route_status','bus_route.id as bus_route_id','B.name as location_start', 'A.name as location_end', 'merchants.name as merchant_name', 'buses.reg_number', 'routes.route_name', 'bus_route.start_time', 'timetables.arrival_time'])
                    ->join('buses', 'buses.id', '=', 'bus_route.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('routes', 'bus_route.route_id', '=', 'routes.id')
                    ->join('timetables', 'bus_route.id', '=', 'timetables.busroute_id')
                    ->join('locations as A', 'A.id', '=', 'timetables.location_id')
                    ->join('locations as B', 'B.id', '=', 'routes.start_location')
                    ->where($this->conditions);
            });
        return $table;
    }

}