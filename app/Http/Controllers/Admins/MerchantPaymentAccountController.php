<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/12/2018
 * Time: 4:34 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\CreateSystemAccount;
use App\Models\MerchantPaymentAccount;
use App\Models\SystemPaymentAccount;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class MerchantPaymentAccountController extends BaseController
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
        $table = $this->createMerchantPaymentAccountTable();
        return view('admins.pages.payment_accounts.merchant_payment_account_index')->with(['accountTable'=>$table]);
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
    protected function createMerchantPaymentAccountTable()
    {
        $table = app(TableList::class)
            ->setModel(MerchantPaymentAccount::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_payment_accounts.index', 'parameters' => []],
                'create' => ['alias' => 'admin.merchant_payment_accounts.create', 'parameters' => []],
                'destroy' => ['alias' => 'admin.merchant_payment_accounts.destroy', 'parameters' => ['id']],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchant_payment_accounts.id as id','merchants.name as merchant_name',
                    'merchant_payment_accounts.account_number as account_number','merchant_payment_accounts.payment_mode',
                    'merchant_payment_accounts.created_at as created_at')
                    ->join('merchants','merchants.id','=','merchant_payment_accounts.merchant_id');
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
        $table->addColumn('name')->setTitle('Merchant name')->setCustomTable('merchants')->useForDestroyConfirmation()->sortByDefault();

        $table->addColumn('payment_mode')->setTitle('Account number');

        $table->addColumn('account_number')->setTitle('Payment method');

        $table->addColumn('created_at')->setTitle('Date created');

        return $table;
    }

}