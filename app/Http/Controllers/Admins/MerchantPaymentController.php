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

    public function __construct()
    {
        parent::__construct();
    }

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

    public function merchantReport(){

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
                $query->select('merchants.id as merchant_id','days.date as date','booking_payments.method as payment_method',
                    \DB::raw('sum(booking_payments.amount) as price'))
                    ->join('bookings','bookings.schedule_id','=','schedules.id')
                    ->join('buses','buses.id','=','schedules.bus_id')
                    ->join('merchants','merchants.id','=','buses.merchant_id')
                    ->join('days','days.id','=','schedules.day_id')
                    ->join('tickets','tickets.booking_id','=','bookings.id')
                    ->join('booking_payments','booking_payments.booking_id','=','bookings.id')
                    ->where('days.date','=','2018-09-07')
                    ->groupBy('days.date','merchants.id','booking_payments.method');
            });

        $table = $this->setSummaryReportColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setSummaryReportColumns($table)
    {
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault()->setCustomTable('days');
        $table->addColumn('method')->setTitle('Payment via')->isSearchable()->setCustomTable('booking_payments');
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments');

        return $table;
    }
}