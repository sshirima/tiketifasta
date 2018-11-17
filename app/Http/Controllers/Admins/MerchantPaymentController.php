<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\BookingPayment;
use App\Models\Merchant;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelBootstrapTableList\TableList;

class MerchantPaymentController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $report = \DB::table('schedules')->select('merchants.id as merchant_id','days.date as date','booking_payments.method as payment_method',
            \DB::raw('sum(booking_payments.amount) as price'))
            ->join('bookings','bookings.schedule_id','=','schedules.id')
            ->join('buses','buses.id','=','schedules.bus_id')
            ->join('merchants','merchants.id','=','buses.merchant_id')
            ->join('days','days.id','=','schedules.day_id')
            ->join('tickets','tickets.booking_id','=','bookings.id')
            ->join('booking_payments','booking_payments.booking_id','=','bookings.id')
            ->where('days.date','=','2018-09-07')
            ->groupBy('days.date','merchant_id','payment_method')->get();

        return $report;
        //'created_at'=>date('Y-m-d')
        //$table = $this->createBookingTable();

        //return view('admins.pages.payments.index_booking_payments')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(BookingPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.booking_payments.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('booking_payments.id as id','booking_payments.payment_ref as payment_ref','booking_payments.booking_id as booking_id',
                    'booking_payments.amount as amount','booking_payments.method as method','booking_payments.phone_number as phone_number'
                    ,'booking_payments.created_at as created_at','booking_payments.updated_at as updated_at')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id');
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
        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable()->sortByDefault();
        $table->addColumn('amount')->setTitle('Amount')->isSearchable();
        $table->addColumn('created_at')->setTitle('Created')->isSearchable();
        $table->addColumn('updated_at')->setTitle('Updated')->isSearchable();

        return $table;
    }
}