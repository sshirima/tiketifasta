<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins\DisbursementReport;

use App\Http\Controllers\Admins\BaseController;
use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingReportController extends BaseController
{
    protected $condition;
    protected $dateCondition;

    public function __construct()
    {
        parent::__construct();
    }

    public function bookingReport(Request $request){

        $table = $this->createBookingTable();

        if ($request->has('date')) {
            $this->dateCondition = $request->input('date');
        }

        if ($request->has('bus_number')) {
            $this->condition[] = ['buses.reg_number','=',$request->get('bus_number')];
        }

        if($request->has('merchant_name')){
            $this->condition[] = ['merchants.name','=',$request->get('merchant_name')];
        }

        return view('admins.pages.reports.collection_reports')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $this->condition[] =['booking_payments.transaction_status','=', BookingPayment::TRANS_STATUS_SETTLED];

        $table = app(TableList::class)
            ->setModel(BookingPayment::class)
            ->setRowsNumber(20)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.booking_payments.index', 'parameters' => []],
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
                    ->where($this->condition)
                    ->whereDate('booking_payments.created_at', '=', $this->dateCondition);
                ;
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
                return $this->getBookingPaymentLabelByStatus( $entity['transaction_status']);
            });

        return $table;
    }

    private function getBookingPaymentLabelByStatus($status){

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