<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/11/2018
 * Time: 10:42 AM
 */

namespace App\Http\Controllers\Merchants;


use App\Models\BookingPayment;
use App\Models\MerchantPayment;
use App\Models\MpesaB2C;
use App\Models\MpesaC2B;
use App\Models\TigoB2C;
use App\Models\TigoOnlineC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class DisbursementTransactionsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function all(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createTransactionsTable();

        return view('merchants.pages.payments.index_all_b2c')->with(['table' => $table]);

    }

    public function tigopesa(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createTigopesaTable();

        return view('merchants.pages.payments.index_tigopesa_b2c')->with(['table' => $table]);

    }

    public function mpesa(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createMpesaTransactionsTable();

        return view('merchants.pages.payments.index_mpesa_b2c')->with(['table' => $table]);

    }

    /**
     * @return mixed
     */
    protected function createTransactionsTable()
    {
        $table = app(TableList::class)
            ->setModel(MerchantPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_schedule_payments.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchant_payments.id as id','merchant_payments.payment_ref as reference','merchant_payments.net_amount as net_amount','merchant_payments.merchant_amount',
                    'merchant_payments.commission_amount','merchant_payments.payment_mode as payment_mode','merchant_payments.payment_stage as payment_stage',
                    'merchant_payments.transfer_status as transfer_status','merchant_payments.created_at as created_at',
                    'merchant_payment_accounts.account_number','merchants.name as merchant_name',
                    'merchant_payments.transaction_status as transaction_status')
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where('merchants.id', $this->merchantId);
            });

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @return mixed
     */
    protected function createMpesaTransactionsTable()
    {
        $table = app(TableList::class)
            ->setModel(MpesaB2C::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.mpesab2c.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('mpesa_b2c.id as id','mpesa_b2c.amount as amount','mpesa_b2c.recipient as recipient','transaction_date','transaction_id',
                    'conversation_id','og_conversation_id','mpesa_receipt','result_type','result_code','result_desc',
                    'working_account_funds','utility_account_funds','charges_paid_funds','mpesa_b2c.status as status',
                    'mpesa_b2c.created_at as created_at','mpesa_b2c.updated_at as updated_at',
                    'mpesa_b2c.transaction_status')
                    ->join('merchant_payments','merchant_payments.id','=','mpesa_b2c.merchant_payment_id')
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where('merchants.id', $this->merchantId);
            });

        $table = $this->setMpesaTableColumns($table);

        return $table;
    }

    /**
     * @return mixed
     */
    protected function createTigopesaTable()
    {
        $table = app(TableList::class)
            ->setModel(TigoB2C::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.tigo_b2c.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigo_b2c.id as id','reference_id','msisdn1','amount','txn_status','txn_message',
                    'txn_id','tigo_b2c.created_at','tigo_b2c.updated_at','tigo_b2c.transaction_status')
                    ->join('merchant_payments','merchant_payments.id','=','tigo_b2c.merchant_payment_id')
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where('merchants.id', $this->merchantId);
            });

        $table = $this->setTigopesaTableColumns($table);
        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('payment_ref')->setTitle('Reference')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return '<a href="'.route('admin.merchant_schedule_payments.details', $entity['id']).'">'.$entity['reference'].'</a>';
        });

        $table->addColumn('account_number')->setTitle('Account#')->setCustomTable('merchant_payment_accounts')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['account_number'];
        });

        $table->addColumn('name')->setTitle('Merchant')->setCustomTable('merchants')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['merchant_name'];
        });

        $table->addColumn('net_amount')->setTitle('Collected')->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['net_amount'];
        });

        $table->addColumn('merchant_amount')->setTitle('Paid')->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['merchant_amount'];
        });
        $table->addColumn('commission_amount')->setTitle('Commission')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['commission_amount'];
        });

        $table->addColumn('payment_mode')->setTitle('Paid via')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['payment_mode'];
        });

        $table->addColumn('created_at')->setTitle('Transaction date')->isSortable()->isSearchable()->sortByDefault('desc')->isCustomHtmlElement(function($entity, $column){
            return $entity['created_at'];
        });

        $table->addColumn('transaction_status ')->setTitle('Transaction status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);
        });

        $table->addColumn('name')->setTitle('Action')->setCustomTable('merchants')->isCustomHtmlElement(function($entity, $column){
            return $entity['transfer_status']?'':$link = '<a href="' . route('admin.merchant_payments.retry_payments',['payment_reference'=>$entity['reference']]) . '" class="label label-primary" role="button">Re-try</a>';
        });

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setMpesaTableColumns($table)
    {
        $table->addColumn('mpesa_receipt')->setTitle('Mpesa receipt')->isSearchable();

        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->isSortable();

        $table->addColumn('recipient')->setTitle('Sent to')->isSearchable();

        $table->addColumn('created_at')->isSortable()->setTitle('Created at')->sortByDefault('desc');

        $table->addColumn('transaction_status')->setTitle('Status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);}
        );

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTigopesaTableColumns($table)
    {
        $table->addColumn('txn_id')->setTitle('Reference');

        $table->addColumn('amount')->setTitle('Amount');

        $table->addColumn('msisdn1')->setTitle('Sent to')->isSearchable();

        $table->addColumn('created_at')->setTitle('Created at')->isSortable()->isSearchable()->sortByDefault('desc');

        $table->addColumn('transaction_status')->setTitle('Status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);
        });
        return $table;
    }

    private function getTransactionStatusLabel($status){

        if ($status == BookingPayment::TRANS_STATUS_SETTLED){
            return '<div class="label label-success">'.'Settled'.'</div>';
        }

        if ($status == BookingPayment::TRANS_STATUS_PENDING){
            return '<div class="label label-warning">'.'Pending'.'</div>';
        }

        if ($status == BookingPayment::TRANS_STATUS_AUTHORIZED){
            return '<div class="label label-danger">'.'Authorized'.'</div>';
        }

        if ($status == BookingPayment::TRANS_STATUS_FAILED){
            return '<div class="label label-danger">'.'Failed'.'</div>';
        }

        if ($status == BookingPayment::TRANS_STATUS_PENDING){
            return '<div class="label label-warning">'.'Pending'.'</div>';
        }

        return '<div class="label label-default">'.'Unknown'.'</div>';
    }
}