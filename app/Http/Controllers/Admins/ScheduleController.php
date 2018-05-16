<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/5/2018
 * Time: 10:30 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Merchant;
use App\Models\Day;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\SchedulesRepository;
use Illuminate\Http\Request;

class ScheduleController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_BUS_TYPES = 'busTypes';
    const PARAM_ROUTES = 'routes';
    const PARAM_MERCHANT = 'merchants';
    const PARAM_BUS = 'bus';

    const VIEW_INDEX = 'admins.pages.schedules.index';
    const VIEW_EDIT = 'merchants.pages.buses.edit';

    public $conditions = array();
    public $scheduleRepo;
    public $routeRepo;
    public $merchantRepo;

    private $entityColumns = array(BusRoute::STATUS.' as '.BusRoute::STATUS,Schedule::STATUS.' as '. Schedule::STATUS,
        SubRoute::SOURCE,'A.name as '.SubRoute::SOURCE,'B.name as '.SubRoute::DESTINATION,
        SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,Day::DATE.' as '.Day::DATE,Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,
        SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME,Merchant::NAME .' as '.Merchant::NAME);

    /**
     * ScheduleController constructor.
     * @param SchedulesRepository $scheduleRepository
     * @param RouteRepository $routeRepository
     * @param MerchantRepository $merchantRepo
     */
    public function __construct(SchedulesRepository $scheduleRepository, RouteRepository $routeRepository, MerchantRepository $merchantRepo)
    {
        parent::__construct();
        $this->scheduleRepo = $scheduleRepository;
        $this->routeRepo = $routeRepository;
        $this->merchantRepo = $merchantRepo;
    }

    public function index(Request $request){

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_MERCHANT] = $this->merchantRepo->getSelectMerchantData(array(__('admin_pages.page_routes_create_fields_select_merchant_default')));

        $this->viewData[self::PARAM_ROUTES] = $this->routeRepo->getSelectRouteData(array('Select routes'));

        $this->viewData[self::PARAM_TABLE]= $this->getBusSchedules($request);

        $request->flash();

        return view(self::VIEW_INDEX)->with($this->viewData);
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
        $table->addColumn(Day::COLUMN_DATE)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_date'))->isSortable()->isSearchable()->sortByDefault()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Day::DATE];
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_bus'))->isSortable()->isSearchable()->setCustomTable(Bus::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::REG_NUMBER].'<br>'.'('.$entity[Merchant::NAME].')';
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


}