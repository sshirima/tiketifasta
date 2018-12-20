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

    public function __construct()
    {
        parent::__construct();
    }

    public function merchantReport(Request $request){

        $table = $this->createReportTable();

        return view('admins.pages.reports.disbursement_reports')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createReportTable()
    {
        $this->condition[] =['merchant_payments.transfer_status','=', MerchantPayment::TRANS_STATUS_SETTLED];
        $table = app(TableList::class)
            ->setModel(MerchantPayment::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.disbursement_reports.merchants', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('merchants.id as merchant_id','merchants.name as merchant_name',
                    \DB::raw('sum(merchant_payments.net_amount) as total_net'),
                    \DB::raw('sum(merchant_payments.merchant_amount) as total_merchant'),
                    \DB::raw('sum(merchant_payments.commission_amount) as total_commission'))
                    ->join('merchant_payment_accounts','merchant_payment_accounts.id','=','merchant_payments.payment_account_id')
                    ->join('merchants','merchant_payment_accounts.merchant_id','=','merchants.id')
                    ->where($this->condition)
                    ->groupBy('merchants.id');
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
        $table->addColumn('name')->setTitle('Merchant')->setCustomTable('merchants')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['merchant_name'];
        });

        $table->addColumn('net_amount')->setTitle('Total collection')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $entity['total_net'];
        });

        $table->addColumn('merchant_amount')->setTitle('Total paid')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['total_merchant'];
        });
        $table->addColumn('commission_amount')->setTitle('Total commission')->isSortable()->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['total_commission'];
        });

        return $table;
    }
}