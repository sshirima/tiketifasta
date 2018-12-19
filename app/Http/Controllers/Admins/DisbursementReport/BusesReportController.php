<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins\CollectionReport;

use App\Http\Controllers\Admins\BaseController;
use App\Models\BookingPayment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BusesReportController extends BaseController
{
    protected $condition;
    protected $routeParameters;

    public function __construct()
    {
        parent::__construct();
    }

    public function busesReport(Request $request){

        if($request->has('merchant_name')){
            $this->condition[] = ['merchants.name','=',$request->get('merchant_name')];
            $this->routeParameters['merchant_name'] = $request->get('merchant_name');
        }

        $table = $this->createBookingTable();

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
                $query->select('buses.reg_number as bus_number',
                    \DB::raw('sum(booking_payments.amount) as total_amount'))
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->where($this->condition)
                    ->groupBy('buses.reg_number');
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
        $table->addColumn('reg_number')->setTitle('Bus reg #')->sortByDefault('desc')->isSortable()->isSearchable()->setCustomTable('buses')->isCustomHtmlElement(function ($entity, $column) {
            $this->routeParameters['bus_number'] = $entity['bus_number'];
            $link = '<a href="' . route('admin.collection_reports.daily', $this->routeParameters) . '">' . $entity['bus_number'] . '</a>';
            return $link;
        });

        $table->addColumn('amount')->setTitle('Collected amount')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_amount'];
            //return '<a href="' . route('admin.merchant_payments.merchant_report', ['merchantId' => $entity['merchant_id'], 'date' => date('Y-m-d', strtotime($entity['date_created']))]) . '">' . $entity['merchant_name'] . '</a>';
        });

        return $table;
    }
}