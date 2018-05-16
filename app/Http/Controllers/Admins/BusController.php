<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:53 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\CreateBusRequest;
use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Seat;
use App\Repositories\Admin\BusRepository;
use App\Repositories\Admin\BusTypeRepository;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Merchant\SeatRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class BusController extends BaseController
{
    const PARAM_TABLE = 'table';
    const PARAM_BUS_TYPES = 'busTypes';
    const PARAM_MERCHANTS = 'merchants';

    const VIEW_INDEX = 'admins.pages.buses.index';
    const VIEW_CREATE = 'admins.pages.buses.create';

    const ROUTE_INDEX = 'admin.buses.index';
    const ROUTE_CREATE = 'admin.buses.create';
    const ROUTE_REMOVE = 'admin.buses.remove';
    const ROUTE_STORE = 'admin.buses.store';

    const MERCHANT_NAME = 'merchant_name';
    const BUS_STATE = 'bus_state';

    private $entityColumns = array(Bus::ID.' as '.Bus::COLUMN_ID,Bus::REG_NUMBER.' as '.Bus::COLUMN_REG_NUMBER,
        Merchant::NAME.' as '.self::MERCHANT_NAME,Bus::STATE. ' as '.self::BUS_STATE,Bus::COLUMN_OPERATION_START,Bus::COLUMN_OPERATION_END);


    private $busRepository;
    private $seatRepo;
    private $busTypeRepo;
    private $merchantRepo;

    public function __construct(BusRepository $busRepository, SeatRepository $seatRepository,MerchantRepository $merchantRepository,BusTypeRepository $busTypeRepository)
    {
        parent::__construct();
        $this->merchantRepo = $merchantRepository;
        $this->busTypeRepo = $busTypeRepository;
        $this->busRepository = $busRepository;
        $this->seatRepo = $seatRepository;
    }

    public function index(Request $request)
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_TABLE]= $this->getBusesTable($request);

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function create()
    {
        $this->getDefaultViewData();

        $this->viewData[self::PARAM_BUS_TYPES]= $this->busTypeRepo->getSelectBusTypeData(array(__('admin_pages.page_routes_create_fields_select_route_default')));

        $this->viewData[self::PARAM_MERCHANTS]= $this->merchantRepo->getSelectMerchantData(array(__('admin_pages.page_routes_create_fields_select_merchant_default')));

        return view(self::VIEW_CREATE)->with($this->viewData);
    }

    public function store(CreateBusRequest $request)
    {
        $bus = $this->busRepository->create($request->all());

        if ($bus->seats()->count() == 0){
            Seat::createBusSeats($bus->id,$bus->bustype_id, $this->seatRepo);
        }

        return redirect(route(self::ROUTE_INDEX));
    }

    public function remove($id)
    {
        $bus = $this->busRepository->findWithoutFail($id);

        if (empty($bus)) {
            $this->getDefaultViewErrorData(__('merchant_pages.bus_retrieve_error'));
            return redirect()->back()->withErrors($this->viewErrorData);
        }

        //Check if all the conditions for deletion meet

        Flash::success(__('merchant_pages.bus_delete_success'));

        return redirect(route(self::ROUTE_INDEX));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getBusesTable(Request $request){

        $this->busRepository->setReturnColumn($this->entityColumns);

        $this->busRepository->setConditions($request);

        $routeTable = $this->busRepository->instantiateBusTable();

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
}