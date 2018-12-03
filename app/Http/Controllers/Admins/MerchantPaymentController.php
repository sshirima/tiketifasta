<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\MerchantPayment;
use App\Models\Schedule;
use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class MerchantPaymentController extends BaseController
{
    use MerchantPaymentProcessor;

    private $merchantCond = array();
    private $dateCondition = array();

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function summaryReport(Request $request)
    {

        $table = $this->summaryReportTable();

        return view('admins.pages.payments.merchant_payments')->with(['summaryReportTable' => $table]);
    }

    /**
     * @param Request $request
     * @param $merchantId
     * @return $this
     */
    public function merchantReport(Request $request, $merchantId)
    {

        $table = $this->merchantReportTable($request, $merchantId);

        return view('admins.pages.payments.merchant_payments')->with(['merchantReportTable' => $table]);
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function unpaid(Request $request)
    {

        $table = $this->unpaidTransactionsTable();

        return view('admins.pages.payments.merchant_unpaid_transactions')->with(['table' => $table]);
    }

    /**
     * @return mixed
     */
    protected function summaryReportTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_payments.summary', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id', 'merchants.name as merchant_name',
                    'booking_payments.method as payment_method', \DB::Raw('DATE(booking_payments.created_at) date_created'),
                    \DB::raw('sum(booking_payments.amount) as price'))
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->groupBy(\DB::raw("DATE(booking_payments.created_at)"), 'merchants.id', 'merchants.name', 'booking_payments.method');
            });

        $table = $this->setSummaryReportColumns($table);

        return $table;
    }

    /**
     * @return mixed
     */
    protected function unpaidTransactionsTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchants_transactions.unpaid', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id', 'merchants.name as merchant_name',
                    'booking_payments.method as payment_method', \DB::Raw('DATE(booking_payments.created_at) date_created'),
                    \DB::raw('sum(booking_payments.amount) as price'))
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->whereNull('booking_payments.merchant_payment_id')
                    ->groupBy(\DB::raw("DATE(booking_payments.created_at)"), 'merchants.id', 'merchants.name', 'booking_payments.method');
            });

        $table = $this->setUnpaidTransactionsColumns($table);

        return $table;
    }

    /**
     * @param Request $request
     * @param $merchantId
     * @return mixed
     */
    protected function merchantReportTable(Request $request, $merchantId)
    {
        $this->merchantCond[] = ['merchants.id', '=', $merchantId];

        if ($request->has('date')) {
            $this->dateCondition = $request->input('date');
        }

        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_payments.summary', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id', 'bookings.firstname as firstname', 'bookings.lastname as lastname'
                    , 'merchants.name as merchant_name', 'booking_payments.created_at as created_at', 'tickets.ticket_ref as ticket_ref',
                    'booking_payments.method as payment_method', 'booking_payments.amount as price', 'booking_payments.created_at as created_at')
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->where($this->merchantCond)
                    ->whereDate('booking_payments.created_at', '=', $this->dateCondition);
            });

        if ($request->has('unpaid')) {

            $table->addQueryInstructions(function ($query) {

                $query->select('merchants.id as merchant_id', 'bookings.firstname as firstname', 'bookings.lastname as lastname'
                    , 'merchants.name as merchant_name', 'booking_payments.created_at as created_at', 'tickets.ticket_ref as ticket_ref',
                    'booking_payments.method as payment_method', 'booking_payments.amount as price', 'booking_payments.created_at as created_at')
                    ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
                    ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
                    ->whereNull('booking_payments.merchant_payment_id')
                    ->where($this->merchantCond)
                    ->whereDate('booking_payments.created_at', '=', $this->dateCondition);
            });
        }

        $table = $this->setMerchantReportColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setSummaryReportColumns($table)
    {
        $table->addColumn('created_at')->setTitle('Payment date')->isSortable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['date_created'];
        });

        $table->addColumn('name')->setTitle('Merchant name')->isSearchable()->sortByDefault('desc')->setCustomTable('merchants')->isCustomHtmlElement(function ($entity, $column) {
            return '<a href="' . route('admin.merchant_payments.merchant_report', ['merchantId' => $entity['merchant_id'], 'date' => date('Y-m-d', strtotime($entity['date_created']))]) . '">' . $entity['merchant_name'] . '</a>';
        });
        $table->addColumn('method')->setTitle('Payment via')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['payment_method'];
        });
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
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
        $table->addColumn('created_at')->setTitle('Transaction time')->isSearchable()->sortByDefault('desc')->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return '<a href="' . route('admin.merchant_payments.merchant_report', $entity['merchant_id']) . '">' . $entity['created_at'] . '</a>';
        });

        $table->addColumn('ticket_ref')->setTitle('Ticket Ref#')->isSearchable()->setCustomTable('tickets')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['ticket_ref'];
        });

        $table->addColumn('firstname')->setTitle('Name')->isSearchable()->setCustomTable('bookings')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['firstname'] . ' ' . $entity['lastname'];
        });

        $table->addColumn('name')->setTitle('Merchant name')->isSearchable()->setCustomTable('merchants')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['merchant_name'];
        });
        $table->addColumn('method')->setTitle('Payment via')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['payment_method'];
        });
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['price'];
        });

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setUnpaidTransactionsColumns($table)
    {
        $table->addColumn('created_at')->setTitle('Payment date')->isSortable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['date_created'];
        });

        $table->addColumn('name')->setTitle('Merchant name')->isSearchable()->sortByDefault('desc')->setCustomTable('merchants')->isCustomHtmlElement(function ($entity, $column) {
            return '<a href="' . route('admin.merchant_payments.merchant_report', ['merchantId' => $entity['merchant_id'], 'date' => date('Y-m-d', strtotime($entity['date_created'])), 'unpaid' => true]) . '">' . $entity['merchant_name'] . '</a>';
        });
        $table->addColumn('method')->setTitle('Payment via')->isSortable()->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['payment_method'];
        });
        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->setCustomTable('booking_payments')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['price'];
        });

        return $table;
    }
}