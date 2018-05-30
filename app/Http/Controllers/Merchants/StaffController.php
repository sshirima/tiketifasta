<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\CreateStaffRequest;
use App\Models\Staff;
use App\Repositories\Merchant\StaffRepository;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class StaffController extends BaseController
{
    private $staffRepo;

    public function __construct(StaffRepository $staffRepo)
    {
        $this->middleware('auth:merchant');
        $this->staffRepo = $staffRepo;
    }

    public function index(){
        $staff_table = app(TableList::class)->setModel(Staff::class)->enableRowsNumberSelector()
            ->setRoutes([
            'index' => ['alias'=>'merchant.staff.index','parameters' => []],
            'create'=> ['alias' => 'merchant.staff.create', 'parameters' => []],
            'destroy'=> ['alias' => 'merchant.staff.remove', 'parameters' => ['id']],
        ]);

        $staff_table->addColumn('firstname')->setTitle('Firstname')->isSortable()->sortByDefault()->isSearchable()->useForDestroyConfirmation();

        $staff_table->addColumn('lastname')->setTitle('Lastname')->isSearchable()->isSortable()->setStringLimit(30);

        $staff_table->addColumn('created_at')->setTitle('Creation date')->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        $staff_table->addColumn('updated_at')->setTitle('Updated date')->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        return view('merchants.pages.staff.index')->with(['merchant'=>auth()->user(),'table'=>$staff_table]);
    }

    public function create(){
        return view('merchants.pages.staff.create')->with(['merchant'=>auth()->user()]);
    }

    public function store(CreateStaffRequest $request){
        $input = $request->all();

        $staff_admin = auth()->user();
        //Save new staff
        $input['merchant_id']= $staff_admin->merchant_id;
        //Encrypt staff password
        $input['password'] = bcrypt($input['password']);

        $staff = $this->staffRepo->create($input);

        if (empty($staff)){
            Flash::error(__('page_staff.error_staff_created'));
        }
        Flash::success(__('page_staff.success_staff_created'));

        return redirect(route('merchant.staff.index'));
    }

    public function remove($id){
        $staff = $this->staffRepo->findWithoutFail($id);

        if ($staff->id === auth()->user()->id){
            Flash::error(__('page_staff.error_cant_delete_you'));

            return redirect(route('merchant.staff.index'));
        }

        if (empty($staff)) {
            Flash::error(__('page_staff.error_staff_deleted'));

            return redirect(route('merchant.staff.index'));
        }

        $this->staffRepo->delete($id);

        Flash::success(__('page_staff.success_staff_deleted'));

        return redirect(route('merchant.staff.index'));
    }

}