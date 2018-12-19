<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\BookingPayment;
use App\Models\MerchantPayment;
use App\Models\Schedule;
use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class MerchantSchedulePaymentController extends BaseController
{
    use MerchantPaymentProcessor;

    private $merchantCond = array();

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retryPayment(Request $request)
    {
        if ($request->has('payment_reference')) {

            $reference = $request->input('payment_reference');
            $merchantPayment = MerchantPayment::where(['payment_ref' => $reference])->first();

            $response = $this->payMerchant($merchantPayment);

            if ($response['status']) {
                Flash::success('Transaction has been re-tried');
                return redirect()->back();
            } else {
                Flash::error( $response['error']);
                return redirect()->back();
            }
        }
        Flash::error( 'Something wrong with the input parameters');
        return redirect()->back();
    }

    public function index(Request $request){

        $table = $this->merchantPaymentsTable( $request);

        return view('admins.pages.payments.merchant_scheduled_payments')->with(['merchantPaymentTable'=>$table]);
    }

    public function details(Request $request, $payId){

        $table = $this->bookingPaymentsTable( $request, $payId);

        return view('admins.pages.payments.merchant_scheduled_payments')->with(['bookingPaymentTable'=>$table]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    protected function merchantPaymentsTable(Request $request)
    {
        if($request->has('date')){
            $this->merchantCond[]=['days.date','=',$request->input('date')];
        }

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
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id');
            });

        $table = $this->setMerchantReportColumns($table);

        return $table;
    }

    /**
     * @param Request $request
     * @param $paymentId
     * @return mixed
     */
    protected function bookingPaymentsTable(Request $request, $paymentId)
    {
        $this->merchantCond[] =['booking_payments.merchant_payment_id','=',$paymentId];

        $table = app(TableList::class)
            ->setModel(BookingPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_schedule_payments.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('booking_payments.id as id','booking_payments.payment_ref as payment_ref','booking_payments.booking_id as booking_id',
                    'booking_payments.amount as amount','booking_payments.method as method','booking_payments.phone_number as phone_number'
                    ,'booking_payments.created_at as created_at','booking_payments.updated_at as updated_at','A.name as from','B.name as to',
                    'merchants.name as merchant_name','buses.reg_number as reg_number',
                    'booking_payments.transaction_status as transaction_status')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('trips', 'bookings.trip_id', '=', 'trips.id')
                    ->join('locations as A', 'A.id', '=', 'trips.source')
                    ->join('locations as B', 'B.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->where($this->merchantCond);
            });

        $table = $this->setBookingPaymentColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setBookingPaymentColumns($table){

        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable();

        $table->addColumn('amount')->setTitle('Amount')->isSortable()->isSearchable();

        $table->addColumn('method')->setTitle('Paid via')->isSortable()->isSearchable();

        $table->addColumn('name')->setTitle('Merchant')->isSortable()->isSearchable()->setCustomTable('merchants')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['merchant_name'];
            });

        $table->addColumn('reg_number')->setTitle('Bus number')->isSortable()->isSearchable()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['reg_number'];
            });

        $table->addColumn('name')->setTitle('From')->isSortable()->isSearchable()->setCustomTable('locations')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['from'];
            });

        $table->addColumn('name')->setTitle('To')->isSortable()->isSearchable()->setCustomTable('locations')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['to'];
            });

        $table->addColumn('created_at')->setTitle('Created')->sortByDefault()->isSortable()->isSearchable();

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setMerchantReportColumns($table)
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

    private function getTransactionStatusLabel($status){

        if ($status == MerchantPayment::TRANS_STATUS_SETTLED){
            return '<div class="label label-success">'.'Settled'.'</div>';
        }

        if ($status == MerchantPayment::TRANS_STATUS_FAILED){
            return '<div class="label label-danger">'.'Failed'.'</div>';
        }

        if ($status == MerchantPayment::TRANS_STATUS_AUTHORIZED){
            return '<div class="label label-warning">'.'Authorized'.'</div>';
        }

        if ($status == MerchantPayment::TRANS_STATUS_POSTED){
            return '<div class="label label-warning">'.'Posted'.'</div>';
        }

        if ($status == MerchantPayment::TRANS_STATUS_PENDING){
            return '<div class="label label-warning">'.'Pending'.'</div>';
        }

        return '<div class="label label-default">'.$status.'</div>';
    }
}