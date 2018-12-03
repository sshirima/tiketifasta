<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\BookingPayment;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingPaymentController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.payments.index_booking_payments')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(BookingPayment::class)
            ->setRowsNumber(10)
            ->setRoutes([
                'index' => ['alias' => 'admin.booking_payments.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('booking_payments.id as id','booking_payments.payment_ref as payment_ref','booking_payments.booking_id as booking_id',
                    'booking_payments.amount as amount','booking_payments.method as method','booking_payments.phone_number as phone_number'
                    ,'booking_payments.created_at as created_at','booking_payments.updated_at as updated_at','A.name as from','B.name as to',
                    'merchants.name as merchant_name','buses.reg_number as reg_number')
                    ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('trips', 'bookings.trip_id', '=', 'trips.id')
                    ->join('locations as A', 'A.id', '=', 'trips.source')
                    ->join('locations as B', 'B.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'trips.bus_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                ;
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
        $table->addColumn('created_at')->setTitle('Created')->sortByDefault('desc')->isSortable()->isSearchable();

        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable();

        $table->addColumn('amount')->setTitle('Amount')->isSortable()->isSearchable();

        $table->addColumn('method')->setTitle('Paid via')->isSortable()->isSearchable();

        $table->addColumn('name')->setTitle('Merchant')->isSortable()->isSearchable()->setCustomTable('merchants')
            ->isCustomHtmlElement(function ($entity, $column) {
            return $entity['merchant_name'];
        });
        $table->addColumn('reg_number')->setTitle('Bus number')->isSortable()->isSearchable()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['reg_number'];
            });
        $table->addColumn('name')->setTitle('From')->isSortable()->isSearchable()->setCustomTable('locations')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['from'];
            });
        $table->addColumn('name')->setTitle('To')->isSortable()->isSearchable()->setCustomTable('locations')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity['to'];
            });
        //$table->addColumn('updated_at')->setTitle('Updated')->isSortable()->isSearchable();

        return $table;
    }
}