<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/15/2018
 * Time: 1:12 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\OutofServiceRequest;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\CancelScheduleComment;
use App\Models\Day;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Repositories\Merchant\SchedulesRepository;
use Illuminate\Http\Request;

class OutofServiceController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_BUS_TYPES = 'busTypes';
    const PARAM_ROUTES = 'routes';
    const PARAM_SCHEDULE = 'schedules';
    const PARAM_BUS = 'bus';

    const VIEW_INDEX = 'merchants.pages.out_of_service.index';
    const VIEW_OOS_CONFIRM = 'merchants.pages.out_of_service.confirm';

    private $entityColumns = array(Day::DATE.' as '.Day::DATE, Schedule::ID.' as '.Schedule::ID,BusRoute::STATUS.' as '.BusRoute::STATUS,Schedule::STATUS.' as '. Schedule::STATUS,
        SubRoute::SOURCE,'A.name as '.SubRoute::SOURCE,'B.name as '.SubRoute::DESTINATION,
        SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,Day::DATE.' as '.Day::DATE,Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,
        SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME);

    public $conditions = array();
    public $scheduleRepo;

    public function __construct(SchedulesRepository $scheduleRepository)
    {
        parent::__construct();
        $this->scheduleRepo = $scheduleRepository;
    }

    public function index(Request $request, $id){

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_ROUTES]= $this->getBusRoutesArray($id);

        $this->viewData[self::PARAM_BUS]= Bus::find($id);

        $request[BusRoute::COLUMN_BUS_ID] = $id;

        $this->viewData[self::PARAM_TABLE]= $this->getBusSchedules($request);

        $request->flash();

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function confirm(Request $request, $id){

        $this->getDefaultViewData();

        $schedule  = Schedule::with(['busRoute.bus:id,reg_number',
            'day:id,date','busRoute.route:id,route_name,start_location','busRoute.subRoutes','busRoute.subRoutes.source','busRoute.subRoutes.destination',
            'busRoute.route.startLocation:id,name','bookings'])->find($id);

        $this->viewData[self::PARAM_BUS]= $schedule->busRoute->bus;

        $this->viewData[self::PARAM_SCHEDULE]= $schedule;

        return view(self::VIEW_OOS_CONFIRM)->with($this->viewData);
    }

    public function change(OutofServiceRequest $request, $id){
        $schedule = Schedule::with(['busRoute.bus:id'])->find($id);

        $schedule->status = !$schedule->status;

        CancelScheduleComment::create([
            CancelScheduleComment::COLUMN_MESSAGE=>$request['details'],
            CancelScheduleComment::COLUMN_SCHEDULE_ID=>$id
        ]);

        $schedule->update();

        return redirect(route('merchant.buses.oos.index',$schedule->busRoute->bus->id));
    }

    /**
     * @param int $id
     * @return array
     */
    public function getBusRoutesArray($id = 0): array
    {
        $merchant = auth()->user()->merchant()->first();
        $routes = array(0 => 'Select route');
        if ($id ==0){
            $busRoutes = $merchant->busRoutes()->select()->first()->with('route:id,route_name')->get();
        } else{
            $busRoutes = $merchant->busRoutes()->select()->first()->with('route:id,route_name')->where(['bus_route.bus_id'=>$id])->get();
        }
        foreach ($busRoutes as $busRoute) {
            $routes[$busRoute->route->id] = $busRoute->route->route_name;
        }
        return $routes;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getBusSchedules(Request $request){

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
        $table->addColumn(Day::COLUMN_DATE)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_date'))->isSortable()->isSearchable()->sortByDefault()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Day::DATE];
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

        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {

                return '<form method="GET" action="'.\route('merchant.buses.oos.confirm',$entity[Schedule::ID]).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <button type="submit" class="btn btn-primary">Select</button>
                        </form>';
            });
    }
}