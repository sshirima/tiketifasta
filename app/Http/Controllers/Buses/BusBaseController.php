<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 9:46 PM
 */

namespace App\Http\Controllers\Buses;


use App\Models\Bus;
use App\Models\Merchant;
use Illuminate\Http\Request;

trait BusBaseController
{
    use BusViewParams;

    protected $busRepository;
    protected $indexPage;
    protected $paramCreate = [];

    /**
     * @param Request $request
     * @return $this
     */
    protected function index(Request $request){
        return view($this->indexPage)->with(['table'=>$this->getBusesTable($request)]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function getBusesTable(Request $request){

        $this->busRepository->initializeTable($request, $this->getFieldsToDisplay());

        $table = $this->busRepository->instantiateTableList();

        $this->setBusesTableColumns($table);

        $table = $this->busRepository->setCustomTable($table);

        return $table;
    }

    /**
     * @param $state
     * @return string
     */
    protected function getBusStateStatus($state){
        $status= '<span class="label label-default">Unknowing</span>';
        if($state == Bus::STATE_DEFAULT_ENABLED){
            $status = '<span class="label label-success">Operational</span>';
        } else if($state  == Bus::STATE_DEFAULT_DISABLED){
            $status = '<span class="label label-danger">Non operational</span>';
        } else if ($state  == Bus::STATE_DEFAULT_SUSPENDED){
            $status = '<span class="label label-warning">Suspended</span>';
        }

        return $status;
    }

    /**
     * @param $table
     */
    protected function setBusesTableColumns($table){

        $this->addMerchantNameColumn($table);

        $this->addRegNumberColumn($table);

        $this->addOperationStartColumn($table);

        $this->addOperationEndColumn($table);

        $this->addBusStateColumn($table);
    }

    protected function getFieldsToDisplay(){
        return [Bus::ID.' as '.'id',Bus::REG_NUMBER.' as '.Bus::COLUMN_REG_NUMBER,
            Merchant::NAME.' as '.Merchant::NAME,
            Bus::OPERATION_START.' as '.Bus::COLUMN_OPERATION_START,
            Bus::OPERATION_END.' as '.Bus::COLUMN_OPERATION_END,
            Bus::STATE.' as '.Bus::COLUMN_STATE];
    }

    /**
     * @param $table
     */
    protected function addOperationEndColumn($table): void
    {
        $table->addColumn(Bus::COLUMN_OPERATION_END)->setTitle(__('admin_page_buses.table_head_buses_operation_end'))
            ->isSortable();
    }

    /**
     * @param $table
     */
    protected function addOperationStartColumn($table): void
    {
        $table->addColumn(Bus::COLUMN_OPERATION_START)->setTitle(__('admin_page_buses.table_head_buses_operation_start'))
            ->isSortable();
    }

    /**
     * @param $table
     */
    protected function addRegNumberColumn($table): void
    {
        $table->addColumn(Bus::COLUMN_REG_NUMBER)->setTitle(__('admin_page_buses.table_head_buses_reg_number'))
            ->isSortable()->isSearchable()->useForDestroyConfirmation();
    }

    /**
     * @param $table
     */
    protected function addMerchantNameColumn($table): void
    {
        $table->addColumn(Merchant::COLUMN_NAME)->setTitle(__('admin_page_buses.table_head_buses_company'))->setCustomTable(Merchant::TABLE)
            ->isSortable()->isSearchable()->sortByDefault()->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Merchant::NAME];
            });
    }

    /**
     * @param $table
     */
    protected function addBusStateColumn($table): void
    {
        $table->addColumn(Bus::COLUMN_STATE)->setTitle(__('admin_page_buses.table_head_buses_state'))
            ->isSortable()->isCustomHtmlElement(function ($entity, $column) {
                return $this->getBusStateStatus($entity[Bus::COLUMN_STATE]);
            });
    }
}