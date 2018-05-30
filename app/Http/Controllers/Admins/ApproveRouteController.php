<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/12/2018
 * Time: 11:54 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Day;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\SubRoute;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Merchant\BusRouteRepository;
use Illuminate\Http\Request;

class ApproveRouteController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_ROUTES = 'routes';
    const PARAM_MERCHANTS = 'merchants';
    const PARAM_BUS_ROUTE = 'busRoute';

    const VIEW_SHOW_BUS_ROUTES = 'admins.pages.approvals.bus_routes';
    const VIEW_SHOW_ROUTE_SCHEDULES = 'admins.pages.approve_timetables.show_timetables';

    const ROUTE_INDEX = 'admin.buses.index';

    const MERCHANT_NAME = 'merchant_name';
    const BUS_STATE = 'bus_state';

    private $entityColumns = array(BusRoute::STATUS . ' as ' . BusRoute::STATUS, Bus::REG_NUMBER . ' as ' . Bus::REG_NUMBER, Merchant::NAME . ' as ' . Merchant::NAME,
        Route::ID . ' as ' . Route::COLUMN_ID, SubRoute::ARRIVAL_TIME . ' as ' . SubRoute::ARRIVAL_TIME,
        Route::ROUTE_NAME, 'A.name as ' . SubRoute::SOURCE, SubRoute::DEPART_TIME . ' as ' . SubRoute::DEPART_TIME,
        'B.name as ' . SubRoute::DESTINATION, BusRoute::ID . ' as ' . BusRoute::ID);

    private $busRouteRepository;
    private $routeRepo;
    private $merchantRepo;


    public function __construct(BusRouteRepository $busSubRouteRepository,
                                RouteRepository $routeRepo, MerchantRepository $merchantRepo)
    {
        parent::__construct();
        $this->busRouteRepository = $busSubRouteRepository;
        $this->routeRepo = $routeRepo;
        $this->merchantRepo = $merchantRepo;
    }

    public function showBusRoutes(Request $request)
    {

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_MERCHANTS] = $this->merchantRepo->getSelectMerchantData(array(__('admin_pages.page_routes_create_fields_select_merchant_default')));

        $this->viewData[self::PARAM_ROUTES] = $this->routeRepo->getSelectRouteData(array('Select routes'));

        $request->flash();

        $request['bus_route_status'] = !$request->filled('bus_route_status') ? 0 : $request['bus_route_status'];

        $this->viewData[self::PARAM_TABLE] = $this->getBusRoutes($request);

        return view(self::VIEW_SHOW_BUS_ROUTES)->with($this->viewData);
    }

    public function approveBusRoute(Request $request, $id)
    {

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_MERCHANTS] = $this->merchantRepo->getSelectMerchantData(array(__('admin_pages.page_routes_create_fields_select_merchant_default')));

        $this->viewData[self::PARAM_ROUTES] = $this->routeRepo->getSelectRouteData(array('Select routes'));

        $request->flash();

        $request['bus_route_id'] = $id;

        $this->viewData[self::PARAM_TABLE] = $this->getRouteSchedules($request);

        $this->viewData[self::PARAM_BUS_ROUTE] = BusRoute::with(['route:id,route_name', 'bus:id,reg_number'])->find($id);

        return view(self::VIEW_SHOW_ROUTE_SCHEDULES)->with($this->viewData);
    }

    public function approveConfirm(Request $request, $id)
    {

        $busRoute = BusRoute::with(['route:id,route_name', 'bus:id,reg_number'])->find($id);

        return view('admins.pages.approve_timetables.confirm')->with(['admin' => auth()->user(), 'busRoute' => $busRoute]);
    }

    public function authorizeBusRoute(Request $request, $id)
    {
        $busRoute = $this->busRouteRepository->findWithoutFail($id);

        if (empty($busRoute)) {
            redirect()->back()->withErrors(['message' => 'Error: Could not find the bus route...']);
        }
        $bus = $busRoute->bus()->first();

        if (empty($bus)) {
            redirect()->back()->withErrors(['message' => 'Error: Could not find the bus for the given route...']);
        }
        $bus->state = 'ENABLED';
        $busRoute->status = 1;

        $busRoute->update();
        $bus->update();

        return redirect(route('admin.bus-routes.index'));
    }

    public function showTimetable()
    {

    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getBusRoutes(Request $request)
    {
        $this->entityColumns = array(
            Route::ROUTE_NAME . ' as ' . Route::ROUTE_NAME,
            BusRoute::STATUS . ' as ' . BusRoute::STATUS,
            Bus::REG_NUMBER . ' as ' . Bus::REG_NUMBER,
            Route::START_TIME . ' as ' . Route::START_TIME,
            Route::END_TIME . ' as ' . Route::END_TIME,
            Merchant::NAME . ' as ' . Merchant::NAME,
            Route::ID . ' as ' . Route::COLUMN_ID,
            Route::ROUTE_NAME, 'A.name as ' . Route::START_LOCATION,
            'B.name as ' . Route::END_LOCATION,
            BusRoute::ID . ' as ' . BusRoute::ID);

        $this->busRouteRepository->setReturnColumn($this->entityColumns);

        $this->busRouteRepository->setConditions($request);

        $routeTable = $this->busRouteRepository->instantiateRoutes();

        $this->setBusRoutesColumns($routeTable);

        return $routeTable;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getRouteSchedules(Request $request)
    {
        array_push($this->entityColumns, Day::DATE . ' as ' . Day::DATE);

        $this->busRouteRepository->setReturnColumn($this->entityColumns);

        $this->busRouteRepository->setConditions($request);

        $routeTable = $this->busRouteRepository->instantiateSchedulesTable();

        $this->setBusScheduleColumns($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    public function setBusRoutesColumns($table): void
    {
        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_route_name'))->sortByDefault()->setCustomTable(Route::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::ROUTE_NAME];
            });

        $table->addColumn(Bus::COLUMN_REG_NUMBER)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_bus'))
            ->isSortable()->isSearchable()->setCustomTable(Bus::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Merchant::NAME] . '<br>' . '(' . $entity[Bus::REG_NUMBER] . ')';
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_source'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::START_LOCATION];
            });
        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_destination'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::END_LOCATION];
            });

        $table->addColumn()->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[BusRoute::STATUS] ?
                    "<span class='label label-success'>Active</span>" :
                    "<span class='label label-danger'>Disabled</span>";
            });
        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {
                $form = '<form method="GET" action="' . \route('admin.bus-route.approve', $entity[BusRoute::ID]) . '" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="' . csrf_token() . '">
                            <button type="submit" class="btn btn-xs btn-success">Approve route</button>
                        </form>';
                return $form;
            });
    }

    /**
     * @param $table
     */
    public function setBusScheduleColumns($table): void
    {
        $table->addColumn(Day::COLUMN_DATE)->setTitle('Date/Time')->isSortable()->isSearchable()->sortByDefault()
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
                $depart = date_create('2018-01-01 ' . $entity[SubRoute::DEPART_TIME]);
                $arrival = date_create('2018-01-01 ' . $entity[SubRoute::ARRIVAL_TIME]);
                return date_diff($depart, $arrival)->h . ' hour(s)';
            });

        $table->addColumn()->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[BusRoute::STATUS] ?
                    "<span style='color: green;'>Active <i class='fas fa-check-circle'></i></span>" :
                    "<span style='color: red;'>Disabled <i class='fas fa-times-circle'></i></span>";
            });
    }

}