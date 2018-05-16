<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\CreateLocationRequest;
use App\Models\Location;
use App\Repositories\Admin\LocationRepository;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class LocationController extends BaseController
{
    private $locationRepo;

    public function __construct(LocationRepository $locationRepo)
    {
        parent::__construct();
        $this->locationRepo = $locationRepo;
    }

    public function index(){
        $staff_table = app(TableList::class)->setModel(Location::class)->setRoutes([
            'index' => ['alias'=>'admin.location.index','parameters' => []],
            'create'=> ['alias' => 'admin.location.create', 'parameters' => []],
            'destroy'=> ['alias' => 'admin.location.remove', 'parameters' => []],
        ])->setRowsNumber(10)->enableRowsNumberSelector();

        $staff_table->addColumn('name')->setTitle(__('admin_pages.page_locations_table_head_location_name'))->isSortable()->sortByDefault()->isSearchable()->useForDestroyConfirmation();

        return view('admins.pages.locations.index')->with(['admin'=>auth()->user(),'table'=>$staff_table]);
    }

    public function create(){

        return view('admins.pages.locations.create')->with(['admin'=>auth()->user()]);
    }

    public function store(CreateLocationRequest $request){
        $input = $request->all();

        $location =$this->locationRepo->create($input);

        if (empty($location)){
            Flash::error(__('admin_pages.page_locations_save_error'));
        }
        Flash::success(__('admin_pages.page_locations_save_success'));

        return redirect(route('admin.location.index'));
    }

    public function edit(){}

    public function update(){}

    public function delete(){}

    public function remove($id){
        $staff = $this->locationRepo->findWithoutFail($id);

        if (empty($staff)) {
            Flash::error(__('admin_pages.page_locations_delete_error'));

            return redirect(route('admin.location.index'));
        }

        $this->locationRepo->delete($id);

        Flash::success(__('admin_pages.page_locations_delete_success'));

        return redirect(route('admin.location.index'));
    }

}