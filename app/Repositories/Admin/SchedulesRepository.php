<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Models\Location;
use App\Models\Day;
use App\Models\ReassignBus;
use App\Models\TicketPrice;
use App\Models\Timetables;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelBootstrapTableList\TableList;

class SchedulesRepository extends BaseRepository
{
    public $conditions = array();
    public $entityColumns = array();
    public $travellingDate;
    public $returnColumns;

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Schedule::class;
    }

    public static function getScheduleById($id, array $relation){
        return Schedule::with($relation)->find($id);
    }

    public static function getBusTimetables($bus){

        $destinations = DB::table('timetables')
            ->select('timetables.location_id', 'locations.name', 'timetables.arrival_time','timetables.depart_time')
            ->join('locations', 'timetables.location_id', '=', 'locations.id')
            ->where(['timetables.bus_id' => $bus->id])->get();
        $routes_array = array();
        $i = 0;

        foreach ($destinations as $destination) {
            $routes_array[$i]['location_id'] = $destination->location_id;
            $routes_array[$i]['location_name'] = $destination->name;
            $routes_array[$i]['arrival_time'] = $destination->arrival_time;
            $routes_array[$i]['depart_time'] = $destination->depart_time;
            $i++;
        }

        return $routes_array;
    }

    public function busTimetables(Request $request){

        $this->getConditions($request);

        $table = $this->getTimetables();

        return $table;
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){
        //Set up the querying conditions here
        if ($request->filled('date')) {
            $this->conditions[Day::DATE] = $request['date'];
        } /*else {
            $now = new \DateTime();
            $this->conditions['operation_days.date'] = $now->format('Y-m-d');
        }*/

        if ($request->filled('route_id')) {
            if ($request['route_id'] > 0) {
                $this->conditions[Route::ID] = $request['route_id'];
            }
        }
        if ($request->filled('merchant_id')) {
            if ($request['merchant_id'] > 0) {
                $this->conditions[Merchant::ID] = $request['merchant_id'];
            }
        }
        if ($request->filled('bus_id')) {
            if ($request['bus_id'] > 0) {
                $this->conditions[Bus::ID] = $request['bus_id'];
            }
        }
        if ($request->filled('date_id')) {
            if ($request['date_id'] > 0) {
                $this->conditions[Day::ID] = $request['date_id'];
            }
        }

        if ($request->filled('reassigned_buses_status')) {
            $this->conditions['reassigned_buses.status'] = $request['reassigned_buses_status'];
        }
    }

    public function reassignedSchedules(Request $request){

        $this->getConditions($request);

        $table = $this->getReassignedSchedules();

        return $table;
    }

    /**
     * @param $scheduleId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getScheduleWithId($scheduleId)
    {
        $dailyTimetable = Schedule::with(['busRoute:id,bus_id',
            'busRoute.bus:id,reg_number,class,bustype_id',
            'busRoute.bus.busType:id,seats', 'bookings'])->select(['id', 'bus_route_id', 'operation_day_id'])->find($scheduleId);
        return $dailyTimetable;
    }

    /**
     * @return mixed
     */
    public function instantiateScheduleTable()
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
                    ->join(SubRoute::TABLE, BusRoute::ID, '=', SubRoute::BUS_ROUTE_ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(Bus::TABLE, Bus::ID, '=', BusRoute::BUS_ID)
                    ->join(Merchant::TABLE, Merchant::ID, '=', Bus::MERCHANT_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });
        return $table;
    }

    /**
     * @param Request $request
     */
    public function setTripConditions(Request $request){
        if ($request->filled('start_location')) {
            $this->conditions[SubRoute::SOURCE] = $request['start_location'];
        }
        if ($request->filled('destination')) {
            $this->conditions[SubRoute::DESTINATION] = $request['destination'];
        }
        if ($request->filled('departing_date')) {
            $this->conditions[Day::DATE] = $request['departing_date'];
        }
        $this->conditions[TicketPrice::TICKET_TYPE] = 'Adult';
        $this->travellingDate = $request['departing_date'];
    }

    public function setColumns($columns){
        $this->returnColumns = $columns;
    }

    /**
     * @return mixed
     */
    public function getScheduledBuses(){
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias'=>'merchant.timetable.index','parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select($this->returnColumns)
                    ->join(BusRoute::TABLE,  BusRoute::ID, '=',Schedule::BUS_ROUTE_ID)
                    ->join(Day::TABLE,  Day::ID, '=',Schedule::DAY_ID)
                    ->join(SubRoute::TABLE, BusRoute::ID, '=', SubRoute::BUS_ROUTE_ID)
                    ->join(TicketPrice::TABLE, TicketPrice::SUB_ROUTE_ID, '=', SubRoute::ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(Bus::TABLE, Bus::ID, '=', BusRoute::BUS_ID)
                    ->join(Merchant::TABLE, Merchant::ID, '=', Bus::MERCHANT_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions)
                    ->where(Bus::OPERATION_START,'<=',$this->travellingDate)
                    ->where(Bus::OPERATION_END,'>=',$this->travellingDate);
            });
        return $table;
    }

    /**
     * @return mixed
     */
    public function getReassignedSchedules()
    {
        $table = app(TableList::class)
            ->setModel(ReassignBus::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select(['bus_route.status as route_status','daily_timetables.status as daily_timetable_status',
                    'daily_timetables.id as daily_timetable_id','operation_days.date', 'B.name as location_start',
                    'A.name as location_end', 'merchants.name as merchant_name', 'buses.reg_number', 'routes.route_name',
                    'reassigned_buses.id as reassigned_buses_id',
                    'bus_route.start_time', 'timetables.arrival_time','reassigned_buses.status as reassigned_buses_status'])
                    ->join('daily_timetables', 'reassigned_buses.daily_timetable_id', '=', 'daily_timetables.id')
                    ->join('operation_days', 'operation_days.id', '=', 'daily_timetables.operation_day_id')
                    ->join('bus_route', 'bus_route.id', '=', 'daily_timetables.bus_route_id')
                    ->join('buses', 'buses.id', '=', 'bus_route.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('routes', 'bus_route.route_id', '=', 'routes.id')
                    ->join('timetables', 'timetables.id', '=', 'daily_timetables.timetable_id')
                    ->join('locations as A', 'A.id', '=', 'timetables.location_id')
                    ->join('locations as B', 'B.id', '=', 'routes.start_location')
                    ->where($this->conditions);
            });
        return $table;
    }

    public function getTimetables(){
        $table = app(TableList::class)
            ->setModel(Timetables::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
            'index' => ['alias'=>'merchant.timetable.index','parameters' => []],
        ])->addQueryInstructions(function ($query) {
            $query->select(['reg_number','routes.route_name','timetables.depart_time',
                'timetables.arrival_time', 'B.name as location_start', 'bus_route.start_time','A.name as location_end'])
                ->join('bus_route', 'timetables.busroute_id', '=', 'bus_route.id')
                ->join('buses', 'bus_route.bus_id', '=', 'buses.id')
                ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                ->join('routes', 'routes.id', '=', 'bus_route.route_id')
                ->join('locations as A', 'A.id', '=', 'timetables.location_id')
                ->join('locations as B', 'B.id', '=', 'routes.start_location')
                ->where($this->conditions);
        });

        return $table;
    }
}