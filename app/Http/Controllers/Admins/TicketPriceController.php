<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/15/2018
 * Time: 9:05 AM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\CreateRouteRequest;
use App\Http\Requests\Admin\CreateTicketPriceDefaultRequest;
use App\Http\Requests\Admin\UpdateTicketPriceDefaultRequest;
use App\Models\BusClass;
use App\Models\Route;
use App\Models\TicketPriceDefault;
use App\Repositories\Admin\BusClassRepository;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\TicketPriceRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TicketPriceController extends BaseController
{
    const VIEW_INDEX = 'admins.pages.ticket_prices.index';
    const VIEW_CREATE = 'admins.pages.ticket_prices.create';
    const VIEW_EDIT = 'admins.pages.ticket_prices.edit';

    const ROUTE_INDEX = 'admin.ticket_prices.index';
    const ROUTE_CREATE = 'admin.ticket_prices.create';
    const ROUTE_STORE = 'admin.ticket_prices.store';
    const ROUTE_EDIT = 'admin.ticket_prices.edit';
    const ROUTE_REMOVE = 'admin.ticket_prices.remove';

    const PARAM_TABLE = 'table';
    const PARAM_LOCATIONS = 'locations';
    const PARAM_BUS_CLASSES = 'busClasses';
    const PARAM_TICKET_PRICE = 'ticketPrice';

    private $ticketPrice;
    private $locationRepo;
    private $busClassRepo;

    private $entityColumns = array(TicketPriceDefault::ID.' as '.TicketPriceDefault::COLUMN_ID,'A.name as '.TicketPriceDefault::START_LOCATION,
        'B.name as '.TicketPriceDefault::LAST_LOCATION,BusClass::NAME.' as '.BusClass::NAME,
        TicketPriceDefault::PRICE.' as '.TicketPriceDefault::PRICE);


    public function __construct(TicketPriceRepository $ticketPrice, LocationRepository $locationRepository, BusClassRepository $busClassRepo)
    {
        parent::__construct();
        $this->ticketPrice = $ticketPrice;
        $this->locationRepo = $locationRepository;
        $this->busClassRepo = $busClassRepo;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TABLE]= $this->getTicketPriceDefaultTable($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_LOCATIONS]= $this->locationRepo->getSelectLocationData(array(__('admin_pages.page_routes_create_fields_select_location_default')));

        $this->viewData[self::PARAM_BUS_CLASSES]= $this->busClassRepo->getSelectBusClassData(array(__('admin_pages.page_ticket_price_create_fields_select_location_default')));

        return view(self::VIEW_CREATE)->with($this->viewData);
    }

    /**
     * @param CreateTicketPriceDefaultRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateTicketPriceDefaultRequest $request)
    {
        $input = $request->all();

        if ($input[TicketPriceDefault::COLUMN_LAST_LOCATION] == $input[TicketPriceDefault::COLUMN_START_LOCATION]) {
            $this->getDefaultViewErrorData(__('admin_pages.page_routes_create_message_error_same_locations'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }

        $input[TicketPriceDefault::COLUMN_BUS_CLASS_ID] = $input[TicketPriceDefault::COLUMN_BUS_CLASS_ID]==0?BusClass::DEFAULT_CLASS_NAME:
            $input[TicketPriceDefault::COLUMN_BUS_CLASS_ID];

        $ticketPrice = $this->ticketPrice->updateOrCreate([
            TicketPriceDefault::COLUMN_START_LOCATION=>$input[TicketPriceDefault::COLUMN_START_LOCATION],
            TicketPriceDefault::COLUMN_LAST_LOCATION=>$input[TicketPriceDefault::COLUMN_LAST_LOCATION],
            TicketPriceDefault::COLUMN_BUS_CLASS_ID=>$input[TicketPriceDefault::COLUMN_BUS_CLASS_ID],
        ],[
            TicketPriceDefault::COLUMN_PRICE=>$input[TicketPriceDefault::COLUMN_PRICE],
        ]);

        if (empty($ticketPrice)) {
            $this->getDefaultViewErrorData(__('admin_pages.page_ticket_price_create_message_save_error'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }

        Flash::success(__('admin_pages.page_ticket_price_create_message_save_success'));

        return redirect(route(self::ROUTE_INDEX));
    }

    public function edit($id){
        $ticketPriceDefault = TicketPriceDefault::with(['busClass','startLocation','lastLocation'])->find($id);

        $this->getDefaultViewData();

        $this->viewData[self::PARAM_LOCATIONS]= $this->locationRepo->getSelectLocationData(array(__('admin_pages.page_routes_create_fields_select_location_default')));

        $this->viewData[self::PARAM_BUS_CLASSES]= $this->busClassRepo->getSelectBusClassData(array(__('admin_pages.page_ticket_price_create_fields_select_location_default')));

        $this->viewData[self::PARAM_TICKET_PRICE]= $ticketPriceDefault;

        return view(self::VIEW_EDIT)->with($this->viewData);
    }

    public function update(UpdateTicketPriceDefaultRequest $request, $id){
        $ticketPrice = $this->ticketPrice->findWithoutFail($id);

        $ticketPrice[TicketPriceDefault::COLUMN_PRICE] = $request[TicketPriceDefault::COLUMN_PRICE];

        $ticketPrice->update();

        Flash::success('Default ticket price updated successful...');

        return redirect(route(self::ROUTE_INDEX));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove($id)
    {
        $route = $this->ticketPrice->findWithoutFail($id);

        if (empty($route)) {
            Flash::error(__('admin_pages.page_routes_create_message_delete_error'));

            return redirect(route(self::ROUTE_INDEX));
        }

        $route->delete();

        Flash::success(__('admin_pages.page_routes_create_message_delete_success'));

        return redirect(route(self::ROUTE_INDEX));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getTicketPriceDefaultTable(Request $request){

        $this->ticketPrice->setReturnColumn($this->entityColumns);

        $this->ticketPrice->setConditions($request);

        $routeTable = $this->ticketPrice->instantiateTicketPriceTable();

        $this->setRouteTableColumn($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    private function setRouteTableColumn($table): void
    {
        $table->addColumn(TicketPriceDefault::COLUMN_START_LOCATION)
            ->setTitle(__('admin_pages.page_routes_index_table_head_from'))
            ->sortByDefault()->isSortable()->isSearchable() ->useForDestroyConfirmation()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPriceDefault::START_LOCATION];
            });

        $table->addColumn(TicketPriceDefault::COLUMN_LAST_LOCATION)->setTitle(__('admin_pages.page_routes_index_table_head_to'))->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPriceDefault::LAST_LOCATION];
            });

        $table->addColumn(TicketPriceDefault::COLUMN_PRICE)->setTitle(__('admin_pages.page_routes_index_table_head_price'))->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPriceDefault::PRICE];
            });

        $table->addColumn(BusClass::COLUMN_CLASS_NAME)->setTitle(__('admin_pages.page_routes_index_table_head_bus_class'))->isSearchable()->setCustomTable(BusClass::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[BusClass::NAME];
            });
    }

}