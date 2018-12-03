<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Staff;
use App\Notifications\MerchantResetPasswordNotification;
use App\Repositories\Merchant\StaffRepository;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Okipa\LaravelBootstrapTableList\TableList;

class StaffController extends BaseController
{
    use SendsPasswordResetEmails;

    private $staffRepo;

    public function __construct(StaffRepository $staffRepo)
    {
        parent::__construct();
        $this->staffRepo = $staffRepo;
    }

    public function index(){

        $staff_table = $this->createStaffTable();

        return view('admins.pages.staff.index')->with(['table'=>$staff_table]);
    }

    /**
     * @return mixed
     */
    protected function createStaffTable()
    {
        $staff_table = app(TableList::class)
            ->setModel(Staff::class)
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_accounts.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('staff.id as id', 'merchants.name as name', 'staff.firstname as firstname', 'staff.lastname as lastname',
                    'staff.email as email', 'staff.created_at as created_at', 'staff.updated_at as updated_at')
                    ->join('merchants', 'merchants.id', '=', 'staff.merchant_id');
            });

        $this->setTableColumns($staff_table);
        return $staff_table;
    }

    /**
     * @param $staff_table
     */
    protected function setTableColumns($staff_table): void
    {
        $staff_table->addColumn('name')->setTitle('Merchant name')->sortByDefault()->isSearchable()->isSortable()->setCustomTable('merchants');

        $staff_table->addColumn('firstname')->setTitle('First name')->isSortable()->isSearchable();

        $staff_table->addColumn('lastname')->setTitle('Last name')->isSearchable()->isSortable()->setStringLimit(30);

        $staff_table->addColumn('email')->setTitle('Email')->isSearchable()->isSortable()->useForDestroyConfirmation();

        $staff_table->addColumn('created_at')->setTitle('Creation date')->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        $staff_table->addColumn('updated_at')->setTitle('Updated date')->isSortable()->setColumnDateFormat('d/m/Y H:i:s');

        $staff_table->addColumn('email')->setTitle(' ')->isCustomHtmlElement(function ($entity, $column) {
            return '<a href="' . route('admin.merchant_accounts.password.reset_form',['email'=>$entity['email']]) . '" class="label label-primary" role="button">Reset password</a>';
        });
    }


    /**
     * @param Request $request
     * @return $this
     */
    public function showPasswordResetForm(Request $request)
    {
        return view('admins.pages.staff.email')->with(['email'=>$request->input('email')]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('merchants');
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