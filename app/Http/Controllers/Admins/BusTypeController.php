<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\CreateBusTypeRequest;
use App\Models\Bustype;
use App\Repositories\Admin\BusTypeRepository;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class BusTypeController extends BaseController
{
    private $busTypeRepository;

    public function __construct(BusTypeRepository $busTypeRepository)
    {
        $this->middleware('auth:admin');
        $this->busTypeRepository = $busTypeRepository;
    }

    public function index(){
        $busType_table = app(TableList::class)->setModel(Bustype::class)->setRoutes([
            'index' => ['alias'=>'admin.bustype.index','parameters' => []],
            'create'=> ['alias' => 'admin.bustype.create', 'parameters' => []],
            'destroy'=> ['alias' => 'admin.bustype.remove', 'parameters' => ['id']],
        ])->setRowsNumber(10);

        $busType_table->addColumn('name')->setTitle(__('admin_pages.page_bustype_index_table_head_route_name'))->isSortable()->sortByDefault()->isSearchable()->useForDestroyConfirmation();

        $busType_table->addColumn('seats')->setTitle(__('admin_pages.page_bustype_index_table_head_number_of_seats'))->isSearchable()->isSortable();

        return view('admins.pages.bus_types.index')->with(['admin'=>auth()->user(),'table'=>$busType_table]);
    }

    public function create(){
        return view('admins.pages.bus_types.create')->with(['admin'=>auth()->user()]);
    }

    public function store(CreateBusTypeRequest $request){
        $input = $request->all();

        $busType =$this->busTypeRepository->create($input);

        if (empty($busType)){
            Flash::error(__('admin_pages.page_bustype_create_message_save_error'));
            return redirect(route('merchant.bustype.index'));
        }
        Flash::success(__('admin_pages.page_bustype_create_message_save_success'));

        return redirect(route('admin.bustype.index'));
    }

    public function edit(){}

    public function update(){}

    public function delete(){}

    public function remove($id){
        $bustype = $this->busTypeRepository->findWithoutFail($id);

        if (empty($bustype)) {
            Flash::error(__('admin_pages.page_bustype_create_message_delete_error'));

            return redirect(route('admin.bustype.index'));
        }

        $this->busTypeRepository->delete($id);

        Flash::success(__('admin_pages.page_bustype_create_message_delete_success'));

        return redirect(route('admin.bustype.index'));
    }

}