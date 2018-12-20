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
use App\Models\Schedule;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class DailyReportController extends BaseController
{

    protected $condition;
    protected $routeParameters;

    public function __construct()
    {
        parent::__construct();
    }

    public function dailyReport(Request $request){

        $table = $this->createBookingTable();

        if($request->has('merchant_name')){
            $this->condition[] = ['merchants.name','=',$request->get('merchant_name')];
            $this->routeParameters['merchant_name'] = $request->get('merchant_name');
        }

        if($request->has('bus_number')){
            $this->condition[] = ['buses.reg_number','=',$request->get('bus_number')];
            $this->routeParameters['bus_number'] = $request->get('bus_number');
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
            ->setModel(Schedule::class)
            ->setRowsNumber(20)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.collection_reports.daily', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select(\DB::Raw('DATE(booking_payments.created_at) paid_date'),
                    \DB::raw('sum(booking_payments.amount) as total_amount'))
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->where($this->condition)
                    ->groupBy(\DB::raw("DATE(booking_payments.created_at)"));
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
        $table->addColumn('created_at')->setTitle('Payment date')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            $this->routeParameters['date'] = $entity['paid_date'];
            return '<a href="' . route('admin.collection_reports.bookings', $this->routeParameters) . '">' . $entity['paid_date'] . '</a>';;
        });

        $table->addColumn('amount')->setTitle('Collected amount')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_amount'];
            //return '<a href="' . route('admin.merchant_payments.merchant_report', ['merchantId' => $entity['merchant_id'], 'date' => date('Y-m-d', strtotime($entity['date_created']))]) . '">' . $entity['merchant_name'] . '</a>';
        });

        return $table;
    }
}