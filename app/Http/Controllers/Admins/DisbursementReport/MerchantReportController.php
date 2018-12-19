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

class MerchantReportController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function merchantReport(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.reports.collection_reports')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(20)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.collection_reports.daily', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select(\DB::Raw('merchants.name as merchant_name'),
                    \DB::raw('sum(booking_payments.amount) as total_amount'))
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->where(['booking_payments.transaction_status'=> BookingPayment::TRANS_STATUS_SETTLED])
                    ->groupBy(\DB::raw("merchants.name"));
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
        $table->addColumn('name')->setTitle('Merchant name')->isSortable()->isSearchable()->setCustomTable('merchants')->isCustomHtmlElement(function ($entity, $column) {
            $link = '<a href="' . route('admin.collection_reports.buses', ['merchant_name' => $entity['merchant_name']]) . '">' . $entity['merchant_name'] . '</a>';
            return $link;
        });

        $table->addColumn('amount')->setTitle('Collected amount')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_amount'];
            //return '<a href="' . route('admin.merchant_payments.merchant_report', ['merchantId' => $entity['merchant_id'], 'date' => date('Y-m-d', strtotime($entity['date_created']))]) . '">' . $entity['merchant_name'] . '</a>';
        });

        return $table;
    }
}