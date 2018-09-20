<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Booking;
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

        return '';
        //return view('admins.pages.payments.index_mpesaC2B')->with(['table'=>$table]);
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
                'index' => ['alias' => 'admin.mpesa_c2B.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('bookings.id as id','mpesa_c2B.amount as amount','mpesa_c2b.account_reference as reference')
                    ->join('booking_payments', 'booking_payments.id', '=', 'mpesa_c2B.booking_payment_id');
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
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault()->setCustomTable('days');
        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status'] == Booking::STATUS_CONFIRMED?
                '<div class="label label-success">'.'Paid'.'</div>':'<div class="label label-warning">'.$entity['status'].'</div>';
        });
        return $table;
    }
}