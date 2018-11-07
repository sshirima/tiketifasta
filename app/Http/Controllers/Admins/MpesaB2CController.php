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

        return view('admins.pages.payments.index_mpesaC2B')->with(['table'=>$table]);
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
                'conversation_id','og_conversation_id','mpesa_receipt','result_type','result_code','working_account_funds','utility_account_funds','charges_paid_funds');
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
        $table->addColumn('amount')->setTitle('Amount')->isSearchable();
        $table->addColumn('recipient')->setTitle('Recipient')->isSearchable();
        $table->addColumn('transaction_date')->setTitle('Transaction date')->isSearchable();
        $table->addColumn('transaction_id')->setTitle('Transaction Id')->isSearchable();
        $table->addColumn('mpesa_receipt')->setTitle('Mpesa receipt')->isSearchable();
        $table->addColumn('result_type')->setTitle('Result type')->isSearchable();
        $table->addColumn('result_code')->setTitle('Result code')->isSearchable();
        $table->addColumn('created_at')->isSortable()->setTitle('Created at');
        $table->addColumn('updated_at')->isSortable()->setTitle('Updated at')->sortByDefault('desc');
        return $table;
    }
}