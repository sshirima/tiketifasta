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

class MerchantReportController extends BaseController
{

    protected $condition;
    protected $routeParameters;
    protected $dateCondition;

    public function __construct()
    {
        parent::__construct();
    }

    public function merchantReport(Request $request){

        if($request->has('merchant_name')){
            $this->condition[] = ['merchants.name','=',$request->get('merchant_name')];
            $this->routeParameters['merchant_name'] = $request->get('merchant_name');
        }

        if($request->has('date')){
            $this->dateCondition = $request->input('date');
            $this->routeParameters['date'] = $request->get('date');
        }

        $table = $this->createReportTable();

        return view('admins.pages.reports.disbursement_reports')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createReportTable()
    {
        $this->condition[] =['merchant_payments.transaction_status','=', MerchantPayment::TRANS_STATUS_SETTLED];
        $table = app(TableList::class)
            ->setModel(MerchantPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.disbursement_reports.merchants', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id','merchants.name as merchant_name',
                    \DB::Raw('DATE(merchant_payments.created_at) paid_date'),
                    \DB::raw('sum(merchant_payments.net_amount) as total_net'),
                    \DB::raw('sum(merchant_payments.merchant_amount) as total_merchant'),
                    \DB::raw('sum(merchant_payments.commission_amount) as total_commission'))
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where($this->condition)
                    ->groupBy('merchants.id', \DB::raw("DATE(merchant_payments.created_at)"));
                if(isset($this->dateCondition)){
                    $query->whereDate('merchant_payments.created_at', '=', $this->dateCondition);
                }
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
            $this->routeParameters['merchant_name'] = $entity['merchant_name'];

            return '<a href="' . route('admin.disbursement_reports.merchants', $this->routeParameters) . '">' . $entity['merchant_name'] . '</a>';;
        });

        $table->addColumn('created_at')->setTitle('Date')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['paid_date'];
        });

        $table->addColumn('net_amount')->setTitle('Total collection')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_net'];
        });

        $table->addColumn('merchant_amount')->setTitle('Merchant payments')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_merchant'];
        });

        $table->addColumn('commission_amount')->setTitle('Commission amount')->isSortable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['total_commission'];
        });

        return $table;
    }
}