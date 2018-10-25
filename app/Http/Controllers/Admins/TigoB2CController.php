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
use App\Models\TigoB2C;
use App\Models\TigoOnlineC2B;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TigoB2CController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createDisplayTable();

        return view('admins.pages.payments.index_tigoB2C')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createDisplayTable()
    {
        $table = app(TableList::class)
            ->setModel(TigoB2C::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.tigo_b2c.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigo_b2c.id as id','reference_id','msisdn1','amount','txn_status','txn_message',
                    'txn_id','tigo_b2c.created_at','tigo_b2c.updated_at');
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
        $table->addColumn('msisdn1')->setTitle('Receiver')->isSearchable();
        $table->addColumn('amount')->setTitle('Reference');
        $table->addColumn('txn_id')->setTitle('Transaction Id');
        $table->addColumn('txn_status')->setTitle('Status')->isSearchable();
        $table->addColumn('txn_message')->setTitle('Status message')->isSearchable();
        $table->addColumn('updated_at')->setTitle('Updated at')->isSortable()->isSearchable();
        $table->addColumn('created_at')->setTitle('Created at')->isSortable()->isSearchable()->sortByDefault('desc');

        /*$table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status'] == Booking::STATUS_CONFIRMED?
                '<div class="label label-success">'.'Paid'.'</div>':'<div class="label label-warning">'.$entity['status'].'</div>';
        });*/
        return $table;
    }
}