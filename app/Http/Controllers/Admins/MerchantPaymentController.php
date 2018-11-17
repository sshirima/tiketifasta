<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class MerchantPaymentController extends BaseController
{

    private $merchantId;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function summaryReport(Request $request){

        /*$report = \DB::table('schedules')->select('merchants.id as merchant_id','days.date as date','booking_payments.method as payment_method',
            \DB::raw('sum(booking_payments.amount) as price'))
            ->join('bookings','bookings.schedule_id','=','schedules.id')
            ->join('buses','buses.id','=','schedules.bus_id')
            ->join('merchants','merchants.id','=','buses.merchant_id')
            ->join('days','days.id','=','schedules.day_id')
            ->join('tickets','tickets.booking_id','=','bookings.id')
            ->join('booking_payments','booking_payments.booking_id','=','bookings.id')
            ->where('days.date','=',date('Y-m-d'))
            ->groupBy('days.date','merchants.id','booking_payments.method')->get();

        return $report;*/
        //'created_at'=>date('Y-m-d')
        $table = $this->summaryReportTable();

        return view('admins.pages.payments.merchant_payments')->with(['summaryReportTable'=>$table]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function merchantReport(Request $request, $id){

        $table = $this->merchantReportTable($id);

        return view('admins.pages.payments.merchant_payments')->with(['merchantReportTable'=>$table]);
    }

    public function busReport(){

    }

    /**
     * @return mixed
     */
    protected function summaryReportTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_payments.summary', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id','merchants.name as merchant_name','days.date as date','booking_payments.method as payment_method',
                    \DB::raw('sum(booking_payments.amount) as price'))
                    ->join('bookings','bookings.schedule_id','=','schedules.id')
                    ->join('buses','buses.id','=','schedules.bus_id')
                    ->join('merchants','merchants.id','=','buses.merchant_id')
                    ->join('days','days.id','=','schedules.day_id')
                    ->join('tickets','tickets.booking_id','=','bookings.id')
                    ->join('booking_payments','booking_payments.booking_id','=','bookings.id')
                    ->groupBy('days.date','merchants.id','merchants.name','booking_payments.method');
            });

        $table = $this->setSummaryReportColumns($table);

        return $table;
    }

    /**
     * @return mixed
     */
    protected function merchantReportTable($merchantId)
    {
        $this->merchantId = $merchantId;

        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_payments.summary', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id','bookings.firstname as firstname','bookings.lastname as lastname'
                    ,'merchants.name as merchant_name','days.date as date','tickets.ticket_ref as ticket_ref',
                    'booking_payments.method as payment_method','booking_payments.amount as price')
                    ->join('bookings','bookings.schedule_id','=','schedules.id')
                    ->join('buses','buses.id','=','schedules.bus_id')
                    ->join('merchants','merchants.id','=','buses.merchant_id')
                    ->join('days','days.id','=','schedules.day_id')
                    ->join('tickets','tickets.booking_id','=','bookings.id')
                    ->join('booking_payments','booking_payments.booking_id','=','bookings.id')
                    ->where('merchants.id','=', $this->merchantId);
            });

        $table = $this->setMerchantReportColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setSummaryReportColumns($table)
    {
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault('desc')->setCustomTable('days')->isCustomHtmlElement(function($entity, $column){
            return $entity['date'];
        });
        $table->addColumn('name')->setTitle('Merchant name')->isSearchable()->setCustomTable('merchants')->isCustomHtmlElement(function($entity, $column){
            return '<a href="'.route('admin.merchant_payments.merchant_report', $entity['merchant_id']).'">'.$entity['merchant_name'].'</a>';
        });
        $table->addColumn('method')->setTitle('Payment via')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function($entity, $column){
            return $entity['payment_method'];
        });
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function($entity, $column){
            return $entity['price'];
        });

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setMerchantReportColumns($table)
    {
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault('desc')->setCustomTable('days')->isCustomHtmlElement(function($entity, $column){
            return '<a href="'.route('admin.merchant_payments.merchant_report', $entity['merchant_id']).'">'.$entity['date'].'</a>';
        });

        $table->addColumn('ticket_ref')->setTitle('Ticket Ref#')->isSearchable()->setCustomTable('tickets')->isCustomHtmlElement(function($entity, $column){
            return $entity['ticket_ref'];
        });

        $table->addColumn('firstname')->setTitle('Name')->isSearchable()->setCustomTable('bookings')->isCustomHtmlElement(function($entity, $column){
            return $entity['firstname'].' '.$entity['lastname'];
        });

        $table->addColumn('name')->setTitle('Merchant name')->isSearchable()->setCustomTable('merchants')->isCustomHtmlElement(function($entity, $column){
            return $entity['merchant_name'];
        });
        $table->addColumn('method')->setTitle('Payment via')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function($entity, $column){
            return $entity['payment_method'];
        });
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function($entity, $column){
            return $entity['price'];
        });

        return $table;
    }
}