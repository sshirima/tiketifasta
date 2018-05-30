<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 10:18 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\Accounts\CreateAdminAccountRequest;
use App\Models\Admin;
use App\Repositories\Admin\AdminAccountsRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AdminAccountsController extends BaseController
{
    protected $adminAccountsRepo;

    /**
     * AdminAccountsController constructor.
     * @param AdminAccountsRepository $accountsRepository
     */
    public function __construct(AdminAccountsRepository $accountsRepository)
    {
        parent::__construct();
        $this->adminAccountsRepo = $accountsRepository;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request){
        return view('admins.pages.accounts.index')->with(['table'=>$this->getAdminAccountsTable($request)]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){
        return view('admins.pages.accounts.create');
    }

    /**
     * @param CreateAdminAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateAdminAccountRequest $request){
        $adminAccount = $this->adminAccountsRepo->create($request->all());

        $this->createFlashResponse($adminAccount,__('admin_page_accounts.account_create_success'),__('admin_page_accounts.account_create_fail'));

        return redirect()->route('admin.admin_accounts.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id){

        if ($this->adminAccountsRepo->deleteAdminAccount($id)){
            Flash::success(__('admin_page_accounts.account_delete_success'));
            return redirect()->route('admin.admin_accounts.index');
        } else {
            Flash::error(__('admin_page_accounts.account_delete_fail'));
            return redirect()->route('admin.admin_accounts.index');
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    private function getAdminAccountsTable(Request $request){

        $adminAccountsTable = $this->adminAccountsRepo->instantiateTableList();

        $adminAccountsTable->setRowsNumber(20);

        $this->setAdminAccountTableColumns($adminAccountsTable);

        return $adminAccountsTable;
    }

    /**
     * Prepare set table columns
     * @param $table
     */
    private function setAdminAccountTableColumns($table):void
    {
        $table->addColumn(Admin::COLUMN_FIRST_NAME)->setTitle(__('admin_page_accounts.table_head_first_name'))
            ->isSortable()->isSearchable()->sortByDefault()->useForDestroyConfirmation();

        $table->addColumn(Admin::COLUMN_LAST_NAME)->setTitle(__('admin_page_accounts.table_head_last_name'))
            ->isSortable()->isSearchable();

        $table->addColumn(Admin::COLUMN_EMAIL)->setTitle(__('admin_page_accounts.table_head_email'))
            ->isSortable()->isSearchable();

        $table->addColumn(Admin::COLUMN_PHONE_NUMBER)->setTitle(__('admin_page_accounts.table_head_phone_number'))
            ->isSortable()->isSearchable();
    }

}