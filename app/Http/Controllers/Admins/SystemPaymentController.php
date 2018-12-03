<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/12/2018
 * Time: 4:34 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\CreateSystemAccount;
use App\Models\SystemPaymentAccount;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class SystemPaymentController extends BaseController
{
    /**
     * SystemPaymentController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request){
        $table = $this->createPaymentTable();
        return view('admins.pages.payment_accounts.index')->with(['table'=>$table]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        return view('admins.pages.payment_accounts.create');
    }

    /**
     * @param CreateSystemAccount $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateSystemAccount $request){
        $input = $request->all();
        $account = SystemPaymentAccount::updateOrCreate(['payment_mode'=>$input['payment_mode']],['account_number'=>$input['account_number']]);
        return redirect(route('admin.payments-accounts.index'));
    }

    /**
     * @return mixed
     */
    protected function createPaymentTable()
    {
        $table = app(TableList::class)
            ->setModel(SystemPaymentAccount::class)
            ->setRowsNumber(10)
            ->setRoutes([
                'index' => ['alias' => 'admin.payments-accounts.index', 'parameters' => []],
                'create' => ['alias' => 'admin.payments-accounts.create', 'parameters' => []],
                'destroy' => ['alias' => 'admin.payments-accounts.destroy', 'parameters' => ['id']],
            ]);

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('payment_mode')->setTitle('Payment method')->useForDestroyConfirmation()->sortByDefault();

        $table->addColumn('account_number')->setTitle('Account number');

        return $table;
    }

}