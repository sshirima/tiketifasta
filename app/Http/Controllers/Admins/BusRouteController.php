<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/13/2018
 * Time: 10:59 PM
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

class BusRouteController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_ROUTES = 'routes';
    const PARAM_MERCHANTS = 'merchants';

    const VIEW_INDEX = 'admins.pages.bus_routes.index';

    const ROUTE_INDEX = 'admin.buses.index';

    const MERCHANT_NAME = 'merchant_name';
    const BUS_STATE = 'bus_state';

    private $entityColumns = array(BusRoute::STATUS.' as '.BusRoute::STATUS, Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,Merchant::NAME.' as '.Merchant::NAME,
        Route::ID.' as '.Route::COLUMN_ID,SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME,
        Route::ROUTE_NAME,'A.name as '.SubRoute::SOURCE,SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,
        'B.name as '.SubRoute::DESTINATION);


    private $busRouteRepository;
    private $routeRepo;
    private $merchantRepo;

    /**
     * BusRouteController constructor.
     * @param BusRouteRepository $busSubRouteRepository
     * @param RouteRepository $routeRepo
     * @param MerchantRepository $merchantRepo
     */
    public function __construct(BusRouteRepository $busSubRouteRepository, RouteRepository $routeRepo, MerchantRepository $merchantRepo)
    {
        parent::__construct();
        $this->busRouteRepository = $busSubRouteRepository;
        $this->routeRepo = $routeRepo;
        $this->merchantRepo = $merchantRepo;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_MERCHANTS] = $this->merchantRepo->getSelectMerchantData(array(__('admin_pages.page_routes_create_fields_select_merchant_default')));

        $this->viewData[self::PARAM_ROUTES] = $this->routeRepo->getSelectRouteData(array('Select routes'));

        $request->flash();

        $request['bus_route_status'] = !$request->filled('bus_route_status') ? $request['bus_route_status'] : 1;

        $this->viewData[self::PARAM_TABLE] = $this->getBusRoutes($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    /**
     * @param $table
     */
    public function setBusRoutesColumns($table): void
    {
        /*$table->addColumn(Day::COLUMN_DATE)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_date'))->isSortable()->isSearchable()->sortByDefault()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Day::DATE];
            });*/

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
                    "<span style='color: green;'>Active <i class='fas fa-check-circle'></i></span>" :
                    "<span style='color: red;'>Disabled <i class='fas fa-times-circle'></i></span>";
            });
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

}