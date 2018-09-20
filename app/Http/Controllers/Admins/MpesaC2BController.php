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
                $query->select('mpesa_c2b.id as id','mpesa_c2b.amount as amount','mpesa_c2b.msisdn as msisdn','booking_payment_id')
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
        $table->addColumn('msisdn')->setTitle('Payment number')->isSearchable()->sortByDefault();

        return $table;
    }
}