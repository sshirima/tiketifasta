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

        return view('admins.pages.payments.index_mpesaC2B')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(TigoOnlineC2B::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.mpesa_c2B.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('bookings.id as id','tigoonline_c2b.amount as amount','tigoonline_c2b.account_reference as reference')
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
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault();
        return $table;
    }
}