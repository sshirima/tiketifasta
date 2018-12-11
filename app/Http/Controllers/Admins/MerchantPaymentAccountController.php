<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/12/2018
 * Time: 4:34 PM
 */

namespace App\Http\Controllers\Admins;


use App\Http\Requests\Admin\CreateMerchantPayAccountRequest;
use App\Http\Requests\Admin\CreateSystemAccount;
use App\Http\Requests\Admin\UpdateMerchantPayAccountRequest;
use App\Models\Merchant;
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
        return view('admins.pages.payment_accounts.merchant_payment_account_index')->with(['table'=>$table]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request){
        $merchants = Merchant::getMerchantSelectArray([__('admin_page_buses.select_merchant_default')]);
        return view('admins.pages.payment_accounts.create')->with(['merchants'=>$merchants]);
    }

    /**
     * @param CreateMerchantPayAccountRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector]
     */
    public function store(CreateMerchantPayAccountRequest $request){

        $input = $request->all();

        MerchantPaymentAccount::updateOrCreate(['payment_mode'=>$input['payment_mode'],'merchant_id'=>$input['merchant_id']],
            ['account_number'=>$input['account_number']]);

        return redirect(route('admin.merchant_payment_accounts.index'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function edit(Request $request, $id){

        $account = MerchantPaymentAccount::with(['merchant'])->find($id);

        if (!isset($account)){
           return view('admins.pages.payment_accounts.create')->with(['account'=>null,'accountId'=>$id]);
        }

        $merchant = [$account->merchant->id=>$account->merchant->name];

        return view('admins.pages.payment_accounts.create')->with(['account'=>$account,'merchant'=>$merchant]);
    }

    public function update(UpdateMerchantPayAccountRequest $request, $id){
        $input = $request->all();

        MerchantPaymentAccount::updateOrCreate(['id'=>$id],
            ['account_number'=>$input['account_number']]);

        return redirect(route('admin.merchant_payment_accounts.index'));
    }

    public function delete(Request $request, $id){
        $account = MerchantPaymentAccount::with(['merchant'])->find($id);

        return view('admins.pages.payment_accounts.merchant_payment_account_delete')->with(['account'=>$account]);
    }

    public function destroy(Request $request, $id){

        $account = MerchantPaymentAccount::with(['merchant'])->find($id);
        $account->delete();

        return redirect(route('admin.merchant_payment_accounts.index'));
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
                'edit' => ['alias' => 'admin.merchant_payment_accounts.edit', 'parameters' => []],
                'destroy' => ['alias' => 'admin.merchant_payment_accounts.delete', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchant_payment_accounts.id as id','merchants.name as merchant_name',
                    'merchant_payment_accounts.account_number as account_number','merchant_payment_accounts.payment_mode',
                    'merchant_payment_accounts.created_at as created_at','merchant_payment_accounts.updated_at as updated_at')
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
        $table->addColumn('name')->setTitle('Merchant name')->setCustomTable('merchants')->useForDestroyConfirmation()->sortByDefault()->isCustomHtmlElement(function($entity, $column){
            return $entity['merchant_name'];
        });

        $table->addColumn('account_number')->setTitle('Account number')->isSearchable();

        $table->addColumn('payment_mode')->setTitle('Payment mode')->isSearchable();

        $table->addColumn('created_at')->setTitle('Date created');

        $table->addColumn('updated_at')->setTitle('Date updated');

        return $table;
    }

}