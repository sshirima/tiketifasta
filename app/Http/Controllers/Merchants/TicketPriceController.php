<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/24/2018
 * Time: 9:41 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\CreateTicketPriceRequest;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Route;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Repositories\Merchant\BusRepository;
use App\Repositories\Merchant\TicketPriceRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class TicketPriceController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_SUB_ROUTE = 'subRoute';
    const PARAM_ROUTES = 'routes';
    const PARAM_BUS = 'bus';

    const VIEW_INDEX = 'merchants.pages.ticket_prices.index';
    const VIEW_CREATE = 'merchants.pages.ticket_prices.create';

    const ROUTE_INDEX = 'merchant.ticket_price.index';
    const ROUTE_CREATE = 'merchant.ticket_price.create';

    private $entityColumns = array('B.name as '.SubRoute::DESTINATION,'A.name as '.SubRoute::SOURCE,
        SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,SubRoute::ID.' as '.SubRoute::ID,
        TicketPrice::PRICE.' as '.TicketPrice::PRICE,
        SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME);


    private $busRepo;
    private $ticketPriceRepo;

    public function __construct(BusRepository $busRepository, TicketPriceRepository $ticketPriceRepository)
    {
        parent::__construct();
        $this->busRepo = $busRepository;
        $this->ticketPriceRepo = $ticketPriceRepository;
    }

    public function index(Request $request, $id){

        $bus = $this->busRepo->findWithoutFail($id);
        $this->getDefaultViewData();

        $request[Bus::ID] = $id;

        $this->viewData[self::PARAM_TABLE]= $this->getTicketPriceTable($request);
        $this->viewData[self::PARAM_BUS]= $bus;

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function create(Request $request, $id){

        $this->getDefaultViewData();

        $subRoute = SubRoute::with(['source','destination',SubRoute::REL_TICKET_PRICE])->find($id);

        $this->viewData[self::PARAM_SUB_ROUTE]= $subRoute;

        $this->viewData[self::PARAM_BUS]= $subRoute->busRoute()->first()->bus()->first();

       return view(self::VIEW_CREATE)->with($this->viewData);
    }

    public function store(CreateTicketPriceRequest $request, $id){
        //Save ticket price
        $input = $request->all();

        $subRoute = SubRoute::with([SubRoute::REL_BUS_ROUTE])->find($id);

        if(empty($subRoute)){
            return redirect()->back()->withErrors(['message'=>'Failed to fetch the sub route model...']);
        }

        $ticketPrice = $this->ticketPriceRepo->updateOrCreate([
            TicketPrice::COLUMN_SUB_ROUTE_ID=>$subRoute->id,
            TicketPrice::COLUMN_TICKET_TYPE=>$input[TicketPrice::COLUMN_TICKET_TYPE],
        ],[
            TicketPrice::COLUMN_PRICE=>$input[TicketPrice::COLUMN_PRICE],
        ]);

        if(empty($ticketPrice)){
            return redirect()->back()->withErrors(['message'=>'Failed to update the price...']);
        }

        Flash::success('Ticket price updated for this bus...');

        return redirect(route(self::ROUTE_INDEX,[$subRoute->busRoute->bus_id]));

    }

    public function edit(Request $request, $subRouteId){
        return redirect(route(self::ROUTE_CREATE,[$subRouteId]));
    }

    public function update(){}

    public function delete($bus_id, $id){
        $ticketPrice = $this->ticketPriceRepo->findWithoutFail($id);

        if(empty($ticketPrice)){
           return redirect()->back()->withErrors(['message'=>'Could not retrieve ticket price...']);
        }
        $ticketPrice->delete();

        Flash::success('Ticket price deleted successful!');

        return redirect(route('merchant.ticket_price.index', $bus_id));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getTicketPriceTable(Request $request){

        $this->ticketPriceRepo->setReturnColumn($this->entityColumns);

        $this->ticketPriceRepo->setConditions($request);

        $routeTable = $this->ticketPriceRepo->instantiateTicketPriceTable();

        $this->setBusTicketPricesTable($routeTable);

        return $routeTable;
    }

    /**
     * @param $table
     */
    public function setBusTicketPricesTable($table): void
    {
        $table->addColumn(SubRoute::COLUMN_SOURCE)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_source'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::SOURCE];
            });
        $table->addColumn(SubRoute::COLUMN_DESTINATION)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_destination'))->sortByDefault()->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::DESTINATION];
            });
        $table->addColumn(TicketPrice::COLUMN_PRICE)->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_price'))->setCustomTable(TicketPrice::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPrice::PRICE] == null?
                    "<span style='color: red;'>Not set </span>"
                    :$entity[TicketPrice::PRICE];
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_time_diff'))->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                $depart = date_create('2018-01-01 '.$entity[SubRoute::DEPART_TIME]);
                $arrival = date_create('2018-01-01 '.$entity[SubRoute::ARRIVAL_TIME]);
                return date_diff( $depart, $arrival )->h .' hour(s)';
            });

        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {

                $createForm = '<form method="GET" action="'.\route('merchant.ticket_price.create',[$entity[SubRoute::ID]]).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <button type="submit" class="btn btn-primary">Set price</button>
                        </form>';
                $editForm = '<form method="GET" action="'.\route('merchant.ticket_price.edit',[$entity[SubRoute::ID]]).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <button type="submit" class="btn btn-success">Update</button>
                        </form>';
                return $entity[TicketPrice::PRICE] == null?
                    $createForm
                    :$editForm;
            });

    }

}