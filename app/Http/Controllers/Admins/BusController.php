<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 12:49 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Buses\BusBaseController;
use App\Http\Requests\Admin\Buses\UpdateBusRequest;
use App\Http\Requests\Admin\CreateBusRequest;
use App\Models\Bus;
use App\Repositories\Admin\Buses\BusRepository;
use App\Repositories\Admin\Buses\BusSeatRepository;
use App\Services\Buses\AuthorizeBuses;
use App\Services\Merchants\CheckMerchantContractStatus;
use App\Services\Trips\TripsAnalyser;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BusController extends BaseController
{
    use BusBaseController, CheckMerchantContractStatus, AuthorizeBuses, TripsAnalyser;

    protected $seatRepository;

    /**
     * BusController constructor.
     * @param BusRepository $busRepository
     * @param BusSeatRepository $seatRepository
     */
    public function __construct(BusRepository $busRepository, BusSeatRepository $seatRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->seatRepository = $seatRepository;
        $this->indexPage = 'admins.pages.buses.index';
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request){
        return view('admins.pages.buses.index')->with(['table'=>$this->createBusesTable()]);
    }

    public function create(){
        return view('admins.pages.buses.create')->with($this->getCreateParams());
    }

    public function store(CreateBusRequest $request)
    {
        $bus = $this->busRepository->create($request->all());

        $this->busRepository->createBusSeats($bus);

        $this->createFlashResponse($bus,__('admin_page_buses.create_success'),__('admin_page_buses.create_fail'));

        return redirect(route('admin.buses.index'));
    }

    public function edit($id){
        $bus = $this->busRepository->findWithoutFail($id);

        //return json_encode($this->checkTripConsistent($bus->trips,'GO'));
        return view('admins.pages.buses.create')->with($this->getEditParams($bus));
    }

    /**
     * @param UpdateBusRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateBusRequest $request, $id){

        $this->busRepository->update($request->all(),$id);

        return redirect(route('admin.buses.index'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id){

        return view('admins.pages.buses.show')->with([
            $this->bus =>$this->busRepository->getBusInformation($id),
            $this->busTypes=>$this->getBusTypeSelectArray(),
            $this->merchants=>$this->getMerchantSelectArray(),
            $this->conditions=>$this->getBusConditionArray()
        ]);
    }

    public function destroy($id){
        return 'buses';
    }
    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authorizeBus(Request $request, $id){

        $bus = $this->busRepository->with(['merchant','route','trips'])->findWithoutFail($id);

        if(isset($bus)){
            $bus->merchant = $this->getMerchantContractStatus($bus->merchant);

            $bus = $this->checkTripPrices($bus);

            $bus->go_trip = $this->checkTripConsistent($bus->trips,'GO');

            $bus->return_trip = $this->checkTripConsistent($bus->trips,'RETURN');
        }

        return view('admins.pages.buses.authorize')->with(['bus'=>$bus]);
    }


    public function enableBus(Request $request, $id){

        $bus = $this->busRepository->findWithoutFail($id);

        $this->busEnable($bus);

        return redirect(route('admin.buses.index'));
    }

    public function disableBus(Request $request, $id){

        $bus = $this->busRepository->findWithoutFail($id);

        $this->busDisable($bus);

        return redirect(route('admin.buses.index'));
    }

    /**
     * @return mixed
     */
    protected function createBusesTable()
    {
        $table = app(TableList::class)
            ->setModel(Bus::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.buses.index', 'parameters' => []],
                'create' => ['alias' => 'admin.buses.create', 'parameters' => []],
                'edit' => ['alias' => 'admin.buses.edit', 'parameters' => ['id']],
                'destroy' => ['alias' => 'admin.buses.destroy', 'parameters' => ['id']],
            ])->addQueryInstructions(function ($query) {
                $query->select('buses.id as id','buses.reg_number as reg_number','buses.state as state',
                    'buses.condition as condition','buses.class as class','bustypes.name as model','bustypes.seats as seats',
                    'merchants.name as merchant_name')
                    ->join('bustypes', 'bustypes.id', '=', 'buses.bustype_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id');
            });

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('reg_number')->setTitle('Bus number')->useForDestroyConfirmation()->sortByDefault()->isSortable()->isSearchable();

        $table->addColumn('name')->setTitle('Company')->isSortable()->isSearchable()->setCustomTable('merchants')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['merchant_name'];
        });

        $table->addColumn()->setTitle('Model')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['model'];
        });

        $table->addColumn('class')->setTitle('Class')->isSortable()->isSearchable();

        $table->addColumn('condition')->setTitle('Condition')->isCustomHtmlElement(function ($entity, $column) {
            return $this->getBusConditionStatus($entity['condition']);
        });

        $this->addBusStateColumn($table);

        $table->addColumn('seats')->setTitle('No of Seats')->isSortable()->isSearchable()->setCustomTable('bustypes');

        return $table;
    }

    /**
     * @param $table
     */
    protected function addBusStateColumn($table): void
    {
        $table->addColumn('state')->setTitle('Bus state')
            ->isSortable()->isCustomHtmlElement(function ($entity, $column) {
                return $this->getBusStateStatus($entity['state']);
            });
    }
    /**
     * @param $state
     * @return string
     */
    protected function getBusStateStatus($state){
        $status= '<span class="label label-default">Unknowing</span>';
        if($state == Bus::STATE_DEFAULT_ENABLED){
            $status = '<span class="label label-success">Enabled</span>';
        } else if($state  == Bus::STATE_DEFAULT_DISABLED){
            $status = '<span class="label label-danger">Disabled</span>';
        } else if ($state  == Bus::STATE_DEFAULT_SUSPENDED){
            $status = '<span class="label label-warning">Suspended</span>';
        }

        return $status;
    }

    /**
     * @param $condition
     * @return string
     */
    protected function getBusConditionStatus($condition){
        $view= '<span class="label label-default">Unknown</span>';
        if($condition == Bus::CONDITION_DEFAULT_OPERATIONAL){
            $view = '<span class="label label-success">Operational</span>';
        } else if($condition  == Bus::CONDITION_DEFAULT_MAINTANANCE){
            $view = '<span class="label label-warning">In maintanance</span>';
        } else if ($condition  == Bus::CONDITION_DEFAULT_ACCIDENT){
            $view = '<span class="label label-danger">In accident</span>';
        }

        return $view;
    }

}