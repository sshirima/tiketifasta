<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/11/2018
 * Time: 10:42 AM
 */

namespace App\Http\Controllers\Merchants;


use App\Models\BookingPayment;
use App\Models\MpesaC2B;
use App\Models\TigoOnlineC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class CollectionTransactionsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function all(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createTransactionsTable();

        return view('merchants.pages.payments.index_all_c2b')->with(['table' => $table]);

    }

    public function tigopesa(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createTigopesaTable();

        return view('merchants.pages.payments.index_tigopesa_c2b')->with(['table' => $table]);

    }

    public function mpesa(Request $request)
    {

        $this->setMerchantId();

        $table = $this->createMpesaTransactionsTable();

        return view('merchants.pages.payments.index_mpesa_c2b')->with(['table' => $table]);

    }

    /**
     * @return mixed
     */
    protected function createTransactionsTable()
    {
        $table = app(TableList::class)
            ->setModel(BookingPayment::class)
            ->setRowsNumber(20)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.collection.transactions.all', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('booking_payments.id as id','booking_payments.payment_ref as payment_ref','booking_payments.booking_id as booking_id',
                    'booking_payments.amount as amount','booking_payments.method as method','booking_payments.phone_number as phone_number'
                    ,'booking_payments.created_at as created_at','booking_payments.updated_at as updated_at','A.name as from','B.name as to',
                    'merchants.name as merchant_name','buses.reg_number as reg_number','booking_payments.transaction_status as transaction_status')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('trips', 'bookings.trip_id', '=', 'trips.id')
                    ->join('locations as A', 'A.id', '=', 'trips.source')
                    ->join('locations as B', 'B.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
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
            ->setModel(MpesaC2B::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.collection.transactions.mpesa', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('mpesa_c2b.id as id','account_reference','mpesa_receipt','initiator','mpesa_c2b.amount as amount','mpesa_c2b.msisdn as msisdn',
                    'booking_payment_id','service_receipt','transaction_id','og_conversation_id','stage','service_status',
                    'mpesa_c2b.authorized_at as authorized_at','mpesa_c2b.created_at as created_at',
                    'mpesa_c2b.transaction_status as transaction_status','mpesa_c2b.msisdn as msisdn')
                    ->join('booking_payments', 'booking_payments.id', '=', 'mpesa_c2b.booking_payment_id')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('trips', 'bookings.trip_id', '=', 'trips.id')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
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
            ->setModel(TigoOnlineC2B::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.collection.transactions.tigopesa', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigoonline_c2b.id as id','tigoonline_c2b.phone_number as phone_number','tigoonline_c2b.amount as amount',
                    'tigoonline_c2b.reference as reference','tigoonline_c2b.created_at as created_at','tigoonline_c2b.updated_at as updated_at',
                    'tigoonline_c2b.status as status','tigoonline_c2b.error_code as error_code',
                    'tigoonline_c2b.transaction_status as transaction_status')
                    ->join('booking_payments', 'booking_payments.id', '=', 'tigoonline_c2b.booking_payment_id')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('trips', 'bookings.trip_id', '=', 'trips.id')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
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
        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable()->isSortable();

        $table->addColumn('amount')->setTitle('Amount')->isSortable()->isSearchable();

        $table->addColumn('name')->setTitle('Merchant/Bus')->isSortable()->isSearchable()->setCustomTable('merchants')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['merchant_name'].'('.$entity['reg_number'].')';
            });

        $table->addColumn('method')->setTitle('Paid via')->isSortable()->isSearchable();

        $table->addColumn('created_at')->setTitle('Created')->sortByDefault('desc')->isSortable()->isSearchable();

        $table->addColumn('transaction_status')->setTitle('Status')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $this->getTransactionStatusLabel( $entity['transaction_status']);
            });

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setMpesaTableColumns($table)
    {
        $table->addColumn('account_reference')->setTitle('Reference#')->isSearchable()->isSortable();

        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->isSortable();

        $table->addColumn('msisdn')->setTitle('Paid by')->isSearchable()->isSortable();

        $table->addColumn('mpesa_receipt')->setTitle('Mpesa Recipient')->isSearchable();

        $table->addColumn('created_at')->setTitle('Transaction date')->isSearchable()->isSortable()->sortByDefault('desc');

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
        $table->addColumn('reference')->setTitle('Reference#')->isSearchable()->isSortable();

        $table->addColumn('amount')->setTitle('Amount')->isSortable();

        $table->addColumn('phone_number')->setTitle('Paid by')->isSortable();

        $table->addColumn('created_at')->setTitle('Transaction date')->isSortable()->isSearchable()->sortByDefault('desc');

        $table->addColumn('status')->setTitle('Status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);
        });

        $table->addColumn('error_code')->setTitle('Error code')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $entity['error_code'];
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