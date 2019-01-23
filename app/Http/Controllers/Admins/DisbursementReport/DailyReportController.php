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
use App\Models\MerchantPayment;
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

        return view('admins.pages.reports.disbursement_reports')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $this->condition[] =['merchant_payments.transaction_status','=', MerchantPayment::TRANS_STATUS_SETTLED];

        $table = app(TableList::class)
            ->setModel(MerchantPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.merchant_schedule_payments.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select(\DB::Raw('DATE(merchant_payments.created_at) paid_date'),
                    \DB::raw('sum(merchant_payments.net_amount) as total_collected'),
                    \DB::raw('sum(merchant_payments.merchant_amount) as merchant_payments'),
                    \DB::raw('sum(merchant_payments.commission_amount) as total_commission')
                )
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where($this->condition)
                    ->groupBy(\DB::raw("DATE(merchant_payments.created_at)"));
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
        $table->addColumn('created_at')->setTitle('Payment date')->isSortable()->isSearchable()->setCustomTable('merchant_payments')->isCustomHtmlElement(function ($entity, $column) {
            $this->routeParameters['date'] = $entity['paid_date'];
            return '<a href="' . route('admin.disbursement_reports.merchants', $this->routeParameters) . '">' . $entity['paid_date'] . '</a>';;
        });

        $table->addColumn('net_amount')->setTitle('Total collection')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_collected'];
        });

        $table->addColumn('merchant_amount')->setTitle('Merchant payments')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['merchant_payments'];
        });

        $table->addColumn('commission_amount')->setTitle('Commission amount')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_commission'];
        });

        return $table;
    }
}