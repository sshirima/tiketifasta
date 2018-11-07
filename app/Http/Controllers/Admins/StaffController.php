<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Admins;


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
        parent::__construct();
        $this->staffRepo = $staffRepo;
    }

    public function index(){
        $staff_table = app(TableList::class)->setModel(Staff::class)->enableRowsNumberSelector()
            ->setRoutes([
            'index' => ['alias'=>'admin.merchant_accounts.index','parameters' => []],
        ])->addQueryInstructions(function ($query) {
                $query->select('staff.id as id','merchants.name as name','staff.firstname as firstname','staff.lastname as lastname',
                    'staff.email as email','staff.created_at as created_at','staff.updated_at as updated_at')
                    ->join('merchants', 'merchants.id', '=', 'staff.merchant_id');
            });

        $staff_table->addColumn('firstname')->setTitle('First name')->isSortable()->isSearchable()->useForDestroyConfirmation();

        $staff_table->addColumn('lastname')->setTitle('Last name')->isSearchable()->isSortable()->setStringLimit(30);

        $staff_table->addColumn('email')->setTitle('Email')->isSearchable()->isSortable();

        $staff_table->addColumn('name')->setTitle('Merchant name')->isSearchable()->isSortable()->setCustomTable('merchants');

        $staff_table->addColumn('created_at')->setTitle('Creation date')->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        $staff_table->addColumn('updated_at')->setTitle('Updated date')->sortByDefault()->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        return view('admins.pages.staff.index')->with(['table'=>$staff_table]);
    }

    /*public function create(){
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
    }*/

}