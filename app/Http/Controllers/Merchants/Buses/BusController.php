<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 12:47 PM
 */

namespace App\Http\Controllers\Merchants\Buses;


use App\Http\Controllers\Buses\BusBaseController;
use App\Http\Controllers\Merchants\BaseController;
use App\Http\Requests\Merchant\UpdateBusRequest;
use App\Models\Bus;
use App\Repositories\Merchant\Buses\BusRepository;

class BusController extends BaseController
{
    use BusBaseController;

    public function __construct(BusRepository $busRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->indexPage = 'merchants.pages.buses.index';
    }

    /**
     *
     */
    public function edit($id){
        $bus = $this->busRepository->findWithoutFail($id);

        return view('merchants.pages.buses.edit')->with($this->getEditParams($bus));
    }


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
     * @param $table
     */
    protected function setBusesTableColumns($table){

        $this->addRegNumberColumn($table);

        $this->addOperationStartColumn($table);

        $this->addOperationEndColumn($table);

        $this->addBusStateColumn($table);
    }

    /**
     * @param $table
     */
    protected function addRegNumberColumn($table): void
    {
        $table->addColumn(Bus::COLUMN_REG_NUMBER)->setTitle(__('admin_page_buses.table_head_buses_reg_number'))
            ->isSortable()->sortByDefault()->isSearchable()->useForDestroyConfirmation();
    }

    protected function getFieldsToDisplay(){
        return [Bus::ID.' as '.'id',Bus::REG_NUMBER.' as '.Bus::COLUMN_REG_NUMBER,
            Bus::OPERATION_START.' as '.Bus::COLUMN_OPERATION_START,
            Bus::OPERATION_END.' as '.Bus::COLUMN_OPERATION_END,
            Bus::STATE.' as '.Bus::COLUMN_STATE];
    }

}