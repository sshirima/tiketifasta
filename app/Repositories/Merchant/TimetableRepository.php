<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant;

use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Models\Location;
use App\Models\Day;
use App\Models\ReassignBus;
use App\Models\Timetables;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelBootstrapTableList\TableList;

class TimetableRepository extends BaseRepository
{
    public $conditions = array();

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
        return Timetables::class;
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

    public function dailyTimetables(Request $request){

        $this->getConditions($request);

        $table = $this->getOperationDays();

        return $table;
    }

    public function reassignedSchedules(Request $request){

        $this->getConditions($request);

        $table = $this->getReassignedSchedules();

        return $table;
    }

    /**
     * @param Request $request
     */
    public function getConditions(Request $request): void
    {
        if ($request->filled('date')) {
            $this->conditions['operation_days.date'] = $request['date'];
        } /*else {
            $now = new \DateTime();
            $this->conditions['operation_days.date'] = $now->format('Y-m-d');
        }*/

        if ($request->filled('route_id')) {
            if ($request['route_id'] > 0) {
                $this->conditions['routes.id'] = $request['route_id'];
            }
        }
        if ($request->filled('merchant_id')) {
            if ($request['merchant_id'] > 0) {
                $this->conditions['merchants.id'] = $request['merchant_id'];
            }
        }
        if ($request->filled('bus_id')) {
            if ($request['bus_id'] > 0) {
                $this->conditions['buses.id'] = $request['bus_id'];
            }
        }
        if ($request->filled('date_id')) {
            if ($request['date_id'] > 0) {
                $this->conditions['operation_days.id'] = $request['date_id'];
            }
        }

        if ($request->filled('reassigned_buses_status')) {
            $this->conditions['reassigned_buses.status'] = $request['reassigned_buses_status'];
        }
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
    public function getOperationDays()
    {
        $table = app(TableList::class)
            ->setModel(Day::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select(['bus_route.status as route_status','daily_timetables.status as daily_timetable_status',
                    'daily_timetables.id as daily_timetable_id','operation_days.date', 'B.name as location_start',
                    'A.name as location_end', 'merchants.name as merchant_name', 'buses.reg_number', 'routes.route_name',
                    'bus_route.start_time', 'timetables.arrival_time'])
                    ->join('daily_timetables', 'daily_timetables.operation_day_id', '=', 'operation_days.id')
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