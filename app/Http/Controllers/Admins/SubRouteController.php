<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/15/2018
 * Time: 9:05 AM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\CreateRouteRequest;
use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Merchant\SubRouteRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class SubRouteController extends BaseController
{
    const VIEW_INDEX = 'admins.pages.sub_routes.index';
    const VIEW_CREATE = 'admins.pages.sub_routes.create';

    const ROUTE_INDEX = 'admin.sub_routes.index';

    const PARAM_TABLE = 'table';
    const PARAM_LOCATIONS = 'locations';
    const PARAM_TRAVELLING_DAYS = 'travellingDays';

    private $subRouteRepo;
    private $locationRepo;

    private $entityColumns = array(Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,Merchant::NAME.' as '.Merchant::NAME,TicketPrice::PRICE.' as '.TicketPrice::PRICE,
        Route::ID.' as '.Route::COLUMN_ID,SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME,
        Route::ROUTE_NAME,'A.name as '.SubRoute::SOURCE,SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,
        'B.name as '.SubRoute::DESTINATION);

    /**
     * SubRouteController constructor.
     * @param SubRouteRepository $ticketPrice
     * @param LocationRepository $locationRepository
     */
    public function __construct(SubRouteRepository $ticketPrice, LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->subRouteRepo = $ticketPrice;
        $this->locationRepo = $locationRepository;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TABLE]= $this->getSubRouteTable($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getSubRouteTable(Request $request){

        $this->subRouteRepo->setReturnColumn($this->entityColumns);

        $this->subRouteRepo->setConditions($request);

        $routeTable = $this->subRouteRepo->instantiateSubRouteTable();

        $this->setSubRouteTableColumns($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    public function setSubRouteTableColumns($table): void
    {

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_bus'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Merchant::NAME].'<br>'.'('.$entity[Bus::REG_NUMBER].')';
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

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_price'))
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPrice::PRICE];
            });

        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_route_name'))->sortByDefault()->setCustomTable(Route::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::COLUMN_ROUTE_NAME];
            });

    }

}