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
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.tigoonline_c2b.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigoonline_c2b.id as id','tigoonline_c2b.amount as amount',
                    'tigoonline_c2b.reference as reference','tigoonline_c2b.created_at as created_at','tigoonline_c2b.updated_at as updated_at')
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
        $table->addColumn('amount')->setTitle('Amount');
        $table->addColumn('reference')->setTitle('Reference')->isSearchable();
        $table->addColumn('updated_at')->setTitle('Updated at')->isSearchable();
        $table->addColumn('created_at')->setTitle('Created at')->isSearchable()->sortByDefault();
        return $table;
    }
}