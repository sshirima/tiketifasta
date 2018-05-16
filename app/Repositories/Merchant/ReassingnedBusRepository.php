<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant;

use App\Models\Bus;
use App\Models\ReassignBus;
use App\Models\Schedule;
use App\Models\Day;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class ReassingnedBusRepository extends BaseRepository
{
    public $conditions = array();
    public $operationDayId = 0;
    public $busId = 0;
    public $seatCounts = 0;


    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return ReassignBus::class;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function reassignableBuses(Request $request){

        $this->getReassignConditions($request);

        $table = $this->getReassignableBuses();

        return $table;
    }

    /**
     * @param Request $request
     */
    public function getReassignConditions(Request $request): void
    {
        if ($request->filled('seatsCount')) {
            if($request['seatsCount'] > 0){
                $this->seatCounts = $request['seatsCount'];
            }
        }

        if ($request->filled('busClass')) {
            $this->conditions['buses.class'] = $request['busClass'];
        }

        if ($request->filled('operationDayId')) {
            if($request['operationDayId'] > 0){
                $this->operationDayId = $request['operationDayId'];
            }
        }

        if ($request->filled('busId')) {
            if($request['busId'] > 0){
                $this->busId = $request['busId'];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getReassignableBuses(){
        $table = app(TableList::class)
            ->setModel(Bus::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias'=>'merchant.timetable.index','parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select(['buses.id as bus_id','reg_number','bustypes.seats as seat_count','buses.class'])
                    ->join('bustypes', 'bustypes.id', '=', 'buses.bustype_id')
                    ->join('bus_route', 'buses.id', '=', 'bus_route.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('daily_timetables', 'daily_timetables.bus_route_id', '=', 'bus_route.id')
                    ->where('buses.id','<>',$this->busId)
                    ->where('bustypes.seats','>=',$this->seatCounts)
                    ->where('daily_timetables.operation_day_id','<>',$this->operationDayId)
                    ->groupBy(['buses.id','reg_number','bustypes.seats','buses.class'])
                    ->where($this->conditions);
            });

        return $table;
    }
}