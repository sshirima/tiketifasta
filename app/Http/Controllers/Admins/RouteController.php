<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/15/2018
 * Time: 9:05 AM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\CreateRouteRequest;
use App\Models\Route;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\RouteRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class RouteController extends BaseController
{
    const VIEW_INDEX = 'admins.pages.routes.index';
    const VIEW_CREATE = 'admins.pages.routes.create';

    const ROUTE_INDEX = 'admin.routes.index';
    const ROUTE_CREATE = 'admin.routes.create';
    const ROUTE_STORE = 'admin.routes.store';
    const ROUTE_REMOVE = 'admin.routes.remove';

    const PARAM_TABLE = 'table';
    const PARAM_LOCATIONS = 'locations';
    const PARAM_TRAVELLING_DAYS = 'travellingDays';

    private $routeRepo;
    private $locationRepo;

    private $entityColumns = array(Route::ID.' as '.Route::COLUMN_ID,Route::ROUTE_NAME,'A.name as '.Route::START_LOCATION,'B.name as '.Route::END_LOCATION);

    /**
     * RouteController constructor.
     * @param RouteRepository $ticketPrice
     * @param LocationRepository $locationRepository
     */
    public function __construct(RouteRepository $ticketPrice, LocationRepository $locationRepository)
    {
        parent::__construct();
        $this->routeRepo = $ticketPrice;
        $this->locationRepo = $locationRepository;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TABLE]= $this->getRouteTable($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TRAVELLING_DAYS]=$this->routeRepo->travellingDaysArray();

        $this->viewData[self::PARAM_LOCATIONS]= $this->locationRepo->getSelectLocationData(array(__('admin_pages.page_routes_create_fields_select_location_default')));

        return view(self::VIEW_CREATE)->with($this->viewData);
    }

    /**
     * @param CreateRouteRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateRouteRequest $request)
    {
        $input = $request->all();

        $destinations = $input[CreateRouteRequest::INPUT_DESTINATIONS];

        if (in_array($input[Route::COLUMN_START_LOCATION], $destinations)) {
            $this->getDefaultViewErrorData(__('admin_pages.page_routes_create_message_error_same_locations'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }

        $input[Route::COLUMN_END_LOCATION]= end($destinations);

        array_push($destinations,$input[Route::COLUMN_START_LOCATION]);

        $input[Route::REL_LOCATIONS] = $destinations;

        $route = $this->routeRepo->create($input);

        if (empty($route)) {
            $this->getDefaultViewErrorData(__('admin_pages.page_routes_create_message_save_error'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }

        Flash::success(__('admin_pages.page_routes_create_message_save_success'));

        return redirect(route(self::ROUTE_INDEX));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function remove($id)
    {
        $route = $this->routeRepo->findWithoutFail($id);

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
    private function getRouteTable(Request $request){

        $this->routeRepo->setReturnColumn($this->entityColumns);

        $this->routeRepo->setConditions($request);

        $routeTable = $this->routeRepo->instantiateRouteTable();
        $routeTable->enableRowsNumberSelector();

        $this->setRouteTableColumn($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    private function setRouteTableColumn($table): void
    {
        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_routes_index_table_head_route_name'))->isSortable()->isSearchable()->sortByDefault()->useForDestroyConfirmation()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::COLUMN_ROUTE_NAME];
            });

        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_routes_index_table_head_start_location'))->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::START_LOCATION];
            });
        $table->addColumn(Route::COLUMN_ROUTE_NAME)->setTitle(__('admin_pages.page_routes_index_table_head_final_location'))->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Route::END_LOCATION];
            });

    }

}