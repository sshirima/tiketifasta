<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/5/2018
 * Time: 10:30 PM
 */

namespace App\Http\Controllers\Merchants;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Day;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Repositories\Merchant\SchedulesRepository;
use Illuminate\Http\Request;

class ScheduleController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_BUS_TYPES = 'busTypes';
    const PARAM_ROUTES = 'routes';
    const PARAM_BUS = 'bus';

    const VIEW_INDEX = 'merchants.pages.schedules.index';
    const VIEW_EDIT = 'merchants.pages.buses.edit';

    public $conditions = array();
    public $scheduleRepo;

    private $entityColumns = array(BusRoute::STATUS.' as '.BusRoute::STATUS,Schedule::STATUS.' as '. Schedule::STATUS,
        SubRoute::SOURCE,'A.name as '.SubRoute::SOURCE,'B.name as '.SubRoute::DESTINATION,
        SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,Day::DATE.' as '.Day::DATE,Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,
        SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME);


    public function __construct(SchedulesRepository $scheduleRepository)
    {
        parent::__construct();
        $this->scheduleRepo = $scheduleRepository;
    }

    public function index(Request $request){

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_ROUTES] = $this->getRouteArray();

        $request->flash();

        $this->viewData[self::PARAM_TABLE]= $this->getBusSchedules($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function busSchedules(Request $request, $id){

        $routes = $this->getRouteArray($id);
        $request['bus_id'] = $id;
        $request->flash();

        return view('merchants.pages.buses.bus_schedules')->with(['merchant'=>auth()->user(),
            'table'=>$this->getBusSchedules($request),'routes'=>$routes,'bus'=>Bus::find($id)]);
    }

    /**
     * @param int $bus_id
     * @return array
     */
    public function getRouteArray($bus_id = 0): array
    {
        $busRoute = new BusRoute();
        $routes = $busRoute->getMerchantRoutesArray($bus_id, auth()->user()->merchant()->first());
        return $routes;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getSchedules(Request $request){

        $this->scheduleRepo->setReturnColumn($this->entityColumns);

        $table = $this->scheduleRepo->busSchedules($request);

        $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getBusSchedules(Request $request){

        $this->scheduleRepo->setReturnColumn($this->entityColumns);

        $this->scheduleRepo->setConditions($request);

        $routeTable = $this->scheduleRepo->instantiateScheduleTable();

        $this->setBusTableColumns($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    public function setBusTableColumns($table): void
    {
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_date'))->isSortable()->isSearchable()->sortByDefault()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Day::DATE];
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_bus'))->isSortable()->isSearchable()->setCustomTable(Bus::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::REG_NUMBER];
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_source'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::SOURCE];
            });
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_destination'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::DESTINATION];
            });
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_depart'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::DEPART_TIME];
            });
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_arrival'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::ARRIVAL_TIME];
            });
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_time_difference'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                $depart = date_create('2018-01-01 '.$entity[SubRoute::DEPART_TIME]);
                $arrival = date_create('2018-01-01 '.$entity[SubRoute::ARRIVAL_TIME]);
                return date_diff( $depart, $arrival )->h .' hour(s)';
            });

        $table->addColumn()->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Schedule::STATUS]&&$entity[BusRoute::STATUS]? "<span style='color: green;'>Active <i class='fas fa-check-circle'></i></span>":
                    "<span style='color: red;'>Disabled <i class='fas fa-times-circle'></i></span>"
                    ;
            });
    }

    /**
     * @param $table
     */
    public function setTableColumns($table): void
    {
        $table->addColumn('date')->setTitle('Date')->isSortable()->isSearchable()->sortByDefault()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->date;
            });

        $table->addColumn('reg_number')->setTitle('Bus reg#')->isSortable()->isSearchable()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->reg_number;
            });

        $table->addColumn()->setTitle('Route/Trip')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->route_name . '</br>' . '(' . $entity->location_start . ' to ' . $entity->location_end . ')';
            });
        $table->addColumn()->setTitle('Depart')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->start_time;
            });

        $table->addColumn()->setTitle('Arrive')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->arrival_time;
            });
        $table->addColumn()->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->daily_timetable_status?
                    "<span style='color: green;'>Active <i class='fas fa-check-circle'></i></span>":
                    "<span style='color: red;'>Disabled <i class='fas fa-times-circle'></i></span>"
                    ;
            });
    }

}