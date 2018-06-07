<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 12:47 PM
 */

namespace App\Http\Controllers\Merchants\Buses;


use App\Http\Controllers\Buses\BusBaseController;
use App\Http\Controllers\Buses\BusRouteBaseController;
use App\Http\Controllers\Merchants\BaseController;
use App\Http\Requests\Merchant\Buses\AssignRoutesBusRequest;
use App\Http\Requests\Merchant\Buses\UpdateBusRequest;
use App\Models\Bus;
use App\Models\Trip;
use App\Repositories\Merchant\Buses\BusRepository;
use App\Repositories\Merchant\Buses\TripRepository;
use App\Services\BusesRoutes\BusRouteManager;
use App\Services\BusesRoutes\TripManager;
use Illuminate\Http\Request;

class BusRoutesController extends BaseController
{
    use BusRouteBaseController;

    protected $busRepository;
    protected $tripRepository;

    public function __construct(BusRepository $busRepository, TripRepository $tripRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->tripRepository = $tripRepository;
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function assignRoute(Request $request, $id){

        $bus = $this->busRepository->findWithoutFail($id);

        $tripCount = $bus->busTrips()->count();

        $request[Trip::COLUMN_BUS_ID] = $id;

        if($tripCount > 0){
            $bus->tripCount = $tripCount;
        }

        return view('merchants.pages.bus_routes.assign')->with($this->getAssignRouteParams($bus,$this->getBusTripTable($request)));
    }

    /**
     * @param AssignRoutesBusRequest $request
     * @param $id
     * @return array
     */
    public function saveBusRoute(AssignRoutesBusRequest $request, $id){

        $sortedTrips = $this->tripRepository->analyseTrips($request->all()['trips']);

        $this->tripRepository->createOrUpdate($sortedTrips, $id);

        $this->busRepository->assignRoute($request, $id);

        return redirect(route('merchant.buses.assign_routes',$id));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBusTripTable(Request $request){

        $this->tripRepository->initializeTable($request, $this->getColumnsToReturn());

        $this->tripRepository->setConditions($request);

        $table = $this->tripRepository->instantiateTableList();

        $this->setTripTableColumns($table);

        $table = $this->tripRepository->setCustomTable($table);

        return $table;
    }

    /**
     * @param $table
     */
    protected function setTripTableColumns($table){

        $table->addColumn(Trip::COLUMN_SOURCE)->setTitle(__('admin_page_buses.table_head_trips_from'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Trip::COLUMN_SOURCE];
            });
        $table->addColumn(Trip::COLUMN_DESTINATION)->setTitle(__('admin_page_buses.table_head_trips_to'))
        ->isCustomHtmlElement(function ($entity, $column) {
            return $entity[Trip::COLUMN_DESTINATION];
        });
        $table->addColumn(Trip::COLUMN_DEPART_TIME)->setTitle(__('admin_page_buses.table_head_trips_depart_time'))
            ->sortByDefault()->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Trip::COLUMN_DEPART_TIME];
            });
        $table->addColumn(Trip::COLUMN_ARRIVAL_TIME)->setTitle(__('admin_page_buses.table_head_trips_arrival_time'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Trip::COLUMN_ARRIVAL_TIME];
            });
        $table->addColumn(Trip::COLUMN_TRAVELLING_DAYS)->setTitle(__('admin_page_buses.table_head_trips_travelling_days'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Trip::COLUMN_TRAVELLING_DAYS];
            });
        $table->addColumn(Trip::COLUMN_STATUS)->setTitle(__('admin_page_buses.table_head_trips_status'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Trip::COLUMN_STATUS]?'<span class="label label-success">Active</span>':'<span class="label label-danger">Inactive</span>';
            });
    }

    protected function getColumnsToReturn(){
        return [
            Trip::ID.' as '.'id',
            'A.name as '.Trip::COLUMN_SOURCE,
            'B.name as '.Trip::COLUMN_DESTINATION,
            Trip::COLUMN_DEPART_TIME,
            Trip::COLUMN_ARRIVAL_TIME,
            Trip::COLUMN_TRAVELLING_DAYS,
            Trip::STATUS.' as '.Trip::COLUMN_STATUS
        ];
    }

}