<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\MpesaC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class MpesaC2BController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.payments.index_mpesaC2B')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(MpesaC2B::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.mpesac2b.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('mpesa_c2b.id as id','account_reference','mpesa_receipt','initiator','mpesa_c2b.amount as amount','mpesa_c2b.msisdn as msisdn',
                    'booking_payment_id','service_receipt','transaction_id','og_conversation_id','stage','service_status',
                    'mpesa_c2b.authorized_at as authorized_at','mpesa_c2b.created_at as created_at',
                    'mpesa_c2b.transaction_status as transaction_status')
                    ->join('booking_payments', 'booking_payments.id', '=', 'mpesa_c2b.booking_payment_id');
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
        $table->addColumn('account_reference')->setTitle('Reference#')->isSearchable()->isSortable();

        $table->addColumn('amount')->setTitle('Amount')->isSearchable()->isSortable();

        $table->addColumn('initiator')->setTitle('Paid by')->isSearchable()->isSortable();

        $table->addColumn('created_at')->setTitle('Transaction date')->isSearchable()->isSortable()->sortByDefault('desc');

        $table->addColumn('service_status')->setTitle('Status')->isSortable()->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionStatusLabel($entity['transaction_status']);}
            );
        //$table->addColumn('mpesa_receipt')->setTitle('Mpesa Recipient')->isSearchable();
        //$table->addColumn('service_receipt')->setTitle('Service ID')->isSearchable();
        //$table->addColumn('transaction_id')->setTitle('Transaction ID')->isSearchable()->isSortable();
        //$table->addColumn('og_conversation_id')->setTitle('Conversation ID')->isSearchable();
        /*$table->addColumn('stage')->setTitle('Stage')->isCustomHtmlElement(function($entity, $column){
            return $entity['stage']== '0'?
                '<div class="label label-success">'.'0(OK)'.'</div>':'<div class="label label-warning">'.$entity['stage'].'(Pending)'.'</div>';
        });*/
        //$table->addColumn('authorized_at')->setTitle('Confirmed at')->isSearchable()->isSortable();



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