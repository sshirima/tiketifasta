<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Booking;
use App\Models\TigoOnlineC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TigoSecureC2BController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.payments.index_tigosecureC2B')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(TigoOnlineC2B::class)
            ->setRowsNumber(10)
            ->setRoutes([
                'index' => ['alias' => 'admin.tigoonline_c2b.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigoonline_c2b.id as id','tigoonline_c2b.phone_number as phone_number','tigoonline_c2b.amount as amount',
                    'tigoonline_c2b.reference as reference','tigoonline_c2b.created_at as created_at','tigoonline_c2b.updated_at as updated_at',
                    'tigoonline_c2b.status as status','tigoonline_c2b.error_code as error_code')
                    ->join('booking_payments', 'booking_payments.id', '=', 'tigoonline_c2b.booking_payment_id');
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
        $table->addColumn('phone_number')->setTitle('Sent from');
        $table->addColumn('amount')->setTitle('Amount');
        $table->addColumn('reference')->setTitle('Reference')->isSearchable();
        /*$table->addColumn('firstname')->setTitle('Name')->isSearchable()->isCustomHtmlElement(function($entity, $column){
            return $entity['firstname'].' '.$entity['lastname'];
        });
        $table->addColumn('email')->setTitle('Email')->isSearchable();*/
        /*$table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status'];
        });*/
        //$table->addColumn('updated_at')->setTitle('Updated at')->isSearchable();
        $table->addColumn('created_at')->setTitle('Transaction date')->isSearchable()->sortByDefault();

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $this->getTransactionLabelByStatus($entity['status']);
        });

        $table->addColumn('error_code')->setTitle('Error code')->isCustomHtmlElement(function($entity, $column){
            return $entity['error_code'];
        });
        return $table;
    }

    private function getTransactionLabelByStatus($status){

        if ($status == 'success'){
            return '<div class="label label-success">'.'Success'.'</div>';
        }

        if ($status == 'fail'){
            return '<div class="label label-danger">'.'Failed'.'</div>';
        }

        if ($status == 'unauthorized'){
            return '<div class="label label-warning">'.'Un-authorized'.'</div>';
        }

        return '<div class="label label-default">'.'Unknown'.'</div>';
    }
}