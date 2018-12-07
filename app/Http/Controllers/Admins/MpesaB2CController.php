<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Booking;
use App\Models\MpesaB2C;
use App\Models\MpesaC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class MpesaB2CController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.payments.index_mpesaB2C')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(MpesaB2C::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.mpesab2c.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('mpesa_b2c.id as id','mpesa_b2c.amount as amount','mpesa_b2c.recipient as recipient','transaction_date','transaction_id',
                'conversation_id','og_conversation_id','mpesa_receipt','result_type','result_code','result_desc',
                    'working_account_funds','utility_account_funds','charges_paid_funds','mpesa_b2c.status as status',
                    'mpesa_b2c.created_at as created_at','mpesa_b2c.updated_at as updated_at',
                    'mpesa_b2c.transaction_status');
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
        $table->addColumn('mpesa_receipt')->setTitle('Mpesa receipt')->isSearchable();

        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->isSortable();

        $table->addColumn('recipient')->setTitle('Sent to')->isSearchable();

        $table->addColumn('created_at')->isSortable()->setTitle('Created at')->sortByDefault('desc');

        $table->addColumn('transaction_status')->setTitle('Status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);}
        );

        //$table->addColumn('transaction_date')->setTitle('Transaction date')->isSearchable();
        //$table->addColumn('transaction_id')->setTitle('Transaction Id')->isSearchable();

        //$table->addColumn('result_code')->setTitle('Result code')->isSearchable();
        //$table->addColumn('result_desc')->setTitle('Result Desc')->isSearchable();
        //$table->addColumn('status')->setTitle('Status')->isSearchable();

        //$table->addColumn('updated_at')->isSortable()->setTitle('Updated at');
        return $table;
    }

    private function getTransactionStatusLabel($status){

        if ($status == MpesaC2B::TRANS_STATUS_SETTLED){
            return '<div class="label label-success">'.'Settled'.'</div>';
        }

        if ($status == MpesaC2B::TRANS_STATUS_FAILED){
            return '<div class="label label-danger">'.'Failed'.'</div>';
        }

        if ($status == MpesaC2B::TRANS_STATUS_AUTHORIZED){
            return '<div class="label label-warning">'.'Authorized'.'</div>';
        }

        if ($status == MpesaC2B::TRANS_STATUS_POSTED){
            return '<div class="label label-warning">'.'Posted'.'</div>';
        }

        return '<div class="label label-default">'.$status.'</div>';
    }
}