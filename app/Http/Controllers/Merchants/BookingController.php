<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Merchants;

use App\Models\Booking;
use App\Services\Bookings\BookingManager;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingController extends BaseController
{

    private $bookingManager;

    public function __construct(BookingManager $bookingManager)
    {
        parent::__construct();
        $this->bookingManager = $bookingManager;
    }

    public function index(Request $request){

        $this->merchantId = auth()->user()->merchant_id;

        $table = $this->createBookingTable();

        return view('merchants.pages.bookings.index')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createBookingTable()
    {
        $table = app(TableList::class)
            ->setModel(Booking::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'merchant.bookings.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('bookings.id as id','days.date as date', 'bookings.firstname as firstname',
                    'bookings.lastname as lastname','bookings.email as email','source.name as source','destination.name as destination',
                    'buses.reg_number as reg_number','bookings.price as price','bookings.status as status','booking_payments.payment_ref as payment_ref',
                    'bookings.created_at as created_at', 'bookings.updated_at as updated_at')
                    ->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('trips', 'trips.id', '=', 'bookings.trip_id')
                    ->join('locations as source', 'source.id', '=', 'trips.source')
                    ->join('locations as destination', 'destination.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('booking_payments', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->where('merchants.id', $this->merchantId);
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
        $table->addColumn('date')->setTitle('Date of travel')->isSortable()->isSearchable()->setCustomTable('days');
        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable()->isSortable()->setCustomTable('booking_payments');
        $table->addColumn('firstname')->setTitle('Customer name')->isSearchable()->isSortable()->isCustomHtmlElement(function($entity, $column){
            return  $entity['firstname'].' '.$entity['lastname'];
        });
        //$table->addColumn('email')->setTitle('Email')->isSearchable()->isSortable();
        $table->addColumn('source')->setTitle('From')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('destination')->setTitle('To')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('price')->setTitle('Price')->isSearchable()->isSortable()->setCustomTable('trips');

        //$table->addColumn('updated_at')->setTitle('Updated at')->isSortable()->isSearchable();
        $table->addColumn('created_at')
            ->setTitle('Date purchased')->isSortable()->isSearchable()->sortByDefault('desc');

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return  $this->getBookingLabelByStatus($entity['status']);
        });
        return $table;
    }

    private function getBookingLabelByStatus($status){

        if ($status == Booking::STATUS_CONFIRMED){
            return '<div class="label label-success">'.'Paid/Confirmed'.'</div>';
        }

        if ($status == Booking::STATUS_CANCELLED){
            return '<div class="label label-danger">'.'Cancelled'.'</div>';
        }

        if ($status == Booking::STATUS_EXPIRED){
            return '<div class="label label-danger">'.'Expired'.'</div>';
        }

        if ($status == Booking::STATUS_PENDING){
            return '<div class="label label-warning">'.'Pending'.'</div>';
        }

        return '<div class="label label-default">'.'Unknown'.'</div>';
    }
}