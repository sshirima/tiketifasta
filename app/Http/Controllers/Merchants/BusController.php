<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/11/2018
 * Time: 10:54 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Controllers\Buses\BusBaseController;
use App\Http\Requests\Merchant\UpdateBusRequest;
use App\Models\Bus;
use App\Repositories\BusRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BusController extends BaseController
{

    use BusBaseController;

    public function __construct(BusRepository $busRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
    }

    public function index(Request $request){

        $this->setMerchantId();

        $table = $this->createBusesTable();

        return view('merchants.pages.buses.index')->with(['busesTable' => $table]);
    }

    /**
     *
     */
    public function edit($id){
        $bus = $this->busRepository->findWithoutFail($id);

        return view('merchants.pages.buses.edit')->with($this->getEditParams($bus));
    }

    /**
     * @param UpdateBusRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateBusRequest $request, $id){

        $this->busRepository->update($request->all(),$id);

        return redirect(route('merchant.buses.index'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id){

        return view('merchants.pages.buses.show')->with([$this->bus =>$this->busRepository->getBusInformation($id)]);
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
                'index' => ['alias' => 'merchant.buses.index', 'parameters' => []],
                'edit' => ['alias' => 'merchant.buses.edit', 'parameters' => ['id']],
            ])->addQueryInstructions(function ($query) {
                $query->select('buses.id as id','buses.reg_number as reg_number','buses.state as state',
                    'buses.condition as condition','buses.class as class','bustypes.name as model','bustypes.seats as seats')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('bustypes', 'bustypes.id', '=', 'buses.bustype_id')
                    ->where('merchants.id', $this->merchantId);
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