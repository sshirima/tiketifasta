<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:53 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\UpdateBusRequest;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Merchant;
use App\Models\Schedule;
use App\Models\Day;
use App\Models\Route;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Models\TicketPriceDefault;
use App\Repositories\Merchant\BusRepository;
use App\Repositories\Merchant\RouteRepository;
use App\Repositories\Merchant\SubRouteRepository;
use App\Repositories\Merchant\TicketPriceRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class BusController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_BUS_TYPES = 'busTypes';
    const PARAM_ROUTES = 'routes';
    const PARAM_BUS = 'bus';

    const VIEW_INDEX = 'merchants.pages.buses.index';
    const VIEW_EDIT = 'merchants.pages.buses.edit';

    const ROUTE_INDEX = 'merchant.buses.index';
    const ROUTE_EDIT = 'merchant.buses.edit';
    const ROUTE_UPDATE = 'merchant.buses.edit';

    const MERCHANT_NAME = 'merchant_name';
    const BUS_STATE = 'bus_state';

    private $entityColumns = array(Bus::ID.' as '.Bus::COLUMN_ID,Bus::REG_NUMBER.' as '.Bus::COLUMN_REG_NUMBER,
        Merchant::NAME.' as '.self::MERCHANT_NAME,Bus::STATE. ' as '.self::BUS_STATE,
        Bus::COLUMN_OPERATION_START,Bus::COLUMN_OPERATION_END);

    private $subRouteEntityColumns = array(Route::ROUTE_NAME,'A.name as '.SubRoute::SOURCE,'B.name as '.SubRoute::DESTINATION,
        SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME);

    private $subRouteRepo;
    private $busRepo;
    private $routeRepo;

    public function __construct(BusRepository $busRepository, RouteRepository $routeRepository, SubRouteRepository $subRouteRepository)
    {
        parent::__construct();
        $this->routeRepo = $routeRepository;
        $this->subRouteRepo = $subRouteRepository;
        $this->busRepo = $busRepository;
    }

    public function index(Request $request)
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TABLE]= $this->getBusesTable($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function edit(Request $request, $id)
    {
        $this->getDefaultViewData();

        $bus = $this->busRepo->with(['busRoutes'])->findWithoutFail($id);

        $this->viewData[self::PARAM_ROUTES]= $this->routeRepo->getAvailableRoutes();

        $request[BusRoute::COLUMN_BUS_ID]= $id;

        $this->viewData[self::PARAM_TABLE]= $this->getExistingSubRoutes($request);

        $this->viewData[self::PARAM_BUS]= $bus;

        return view(self::VIEW_EDIT)->with($this->viewData);
    }

    public function update(UpdateBusRequest $request, $id)
    {
        $input = $request->all();

        //Create bus_route
        $busRoute = BusRoute::firstOrCreate([
            BusRoute::COLUMN_ROUTE_ID=> $input['route_id'],
            BusRoute::COLUMN_BUS_ID=> $id,
        ]);

        //Create day and schedules
        $trip_dates = explode(',',$request->all()['trip_dates']);
        //Confirm if the date has been assigned to another route

        foreach ($trip_dates as $date) {
            $opDay = Day::firstOrCreate(['date' => $date]);
            if (!empty($opDay)) {
                if(Schedule::notScheduled($id, $opDay->id)){
                    Schedule::firstOrCreate([
                        'bus_route_id'=>$busRoute->id,
                        'day_id'=>$opDay->id,
                        'status'=>1,
                    ]);
                    Flash::success('Date assigned to the route: '.$opDay->date.' ');
                } else{
                    Flash::warning('Date already assigned another route, so this date was not assigned: Date: '.$opDay->date.' ');
                }
            }
        }
        if (empty($busRoute)) {
            $this->getDefaultViewErrorData(__('merchant_pages.save_bus_route_error'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }
        //Create sub routes
        $i =0;
        $sources = $input['source'];
        $destinations = $input['destination'];
        $arrivalTime = $input['arrival_time'];
        $departTime = $input['depart_time'];
        $travellingDays = $input['travelling_days'];
        foreach ($sources as $source){
            $subRoute = SubRoute::updateOrCreate([
                'source'=>$source,
                'destination'=>$destinations[$i],
                'bus_route_id'=>$busRoute->id,
            ],[
                'arrival_time'=>$arrivalTime[$i],
                'depart_time'=>$departTime[$i],
                'travelling_days'=>$travellingDays[$i],
            ]);

            //Create ticket price
            TicketPriceRepository::createDefaultPrice($subRoute);
        }

        return redirect(route(self::ROUTE_EDIT,$id));
    }


    /**
     * @param Request $request
     * @return mixed
     */
    private function getExistingSubRoutes(Request $request){

        $this->subRouteRepo->setReturnColumn($this->subRouteEntityColumns);

        $this->subRouteRepo->setConditions($request);

        $routeTable = $this->subRouteRepo->instantiateSubRouteTable();

        $this->setSubRouteTableColumns($routeTable);

        return $routeTable;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getBusesTable(Request $request){

        $this->busRepo->setReturnColumn($this->entityColumns);

        $this->busRepo->setConditions($request);

        $routeTable = $this->busRepo->instantiateBusTable();

        $this->setBusTableColumns($routeTable);

        return $routeTable;
    }
    /**
     * @param $table
     */
    public function setBusTableColumns($table): void
    {
        $table->addColumn(Bus::COLUMN_REG_NUMBER)->setTitle(__('admin_pages.page_bus_index_table_head_reg_number'))->isSortable()->isSearchable()->sortByDefault()->useForDestroyConfirmation()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::COLUMN_REG_NUMBER];
            });
        $table->addColumn(Merchant::COLUMN_NAME)->setTitle(__('admin_pages.page_bus_index_table_head_merchant'))->isSortable()->isSearchable()->setCustomTable(Merchant::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[self::MERCHANT_NAME];
            });
        $table->addColumn(Bus::COLUMN_STATE)->setTitle(__('admin_pages.page_bus_index_table_head_state'))->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[self::BUS_STATE];
            });
        $table->addColumn(Bus::COLUMN_OPERATION_START)->setTitle(__('admin_pages.page_bus_index_table_head_operation_start'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::COLUMN_OPERATION_START];
            });
        $table->addColumn(Bus::COLUMN_OPERATION_END)->setTitle(__('admin_pages.page_bus_index_table_head_operation_end'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::COLUMN_OPERATION_END];
            });

    }

    /**
     * @param $table
     */
    public function setSubRouteTableColumns($table): void
    {
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

        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_route_name'))->sortByDefault()->setCustomTable(Route::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::COLUMN_ROUTE_NAME];
            });

    }
}