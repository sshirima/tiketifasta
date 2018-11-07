<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/8/2018
 * Time: 7:26 PM
 */

namespace App\Http\Controllers\Admins;


use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Day;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Services\Bookings\BookingManager;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingController extends BaseController
{
    public $conditions = array();
    private $bookingManager;

    public function __construct(BookingManager $bookingManager)
    {
        parent::__construct();
        $this->bookingManager = $bookingManager;
    }

    public function index(Request $request){

        $table = $this->createBookingTable();

        return view('admins.pages.bookings.index')->with(['bookingTable'=>$table]);
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
                'index' => ['alias' => 'admin.bookings.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('bookings.id as id','days.date as date', 'bookings.firstname as firstname',
                    'bookings.lastname as lastname','bookings.email as email','source.name as source','destination.name as destination',
                    'buses.reg_number as reg_number','trips.price as price','bookings.status as status','booking_payments.payment_ref as payment_ref',
                    'bookings.created_at as created_at', 'bookings.updated_at as updated_at')
                    ->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('trips', 'trips.id', '=', 'bookings.trip_id')
                    ->join('locations as source', 'source.id', '=', 'trips.source')
                    ->join('locations as destination', 'destination.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('booking_payments', 'bookings.id', '=', 'booking_payments.booking_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id');
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
        $table->addColumn('date')->setTitle('Date')->isSortable()->isSearchable()->setCustomTable('days');
        $table->addColumn('payment_ref')->setTitle('Reference')->isSearchable()->isSortable()->setCustomTable('booking_payments');
        $table->addColumn('firstname')->setTitle('First name')->isSearchable()->isSortable();
        $table->addColumn('lastname')->setTitle('Last name')->isSearchable()->isSortable();
        $table->addColumn('email')->setTitle('Email')->isSearchable()->isSortable();
        $table->addColumn('source')->setTitle('From')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('destination')->setTitle('To')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('price')->setTitle('Price')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status'] == Booking::STATUS_CONFIRMED?
                '<div class="label label-success">'.'Paid'.'</div>':'<div class="label label-warning">'.$entity['status'].'</div>';
        });
        $table->addColumn('updated_at')->setTitle('Updated at')->isSortable()->isSearchable()->sortByDefault('desc');
        $table->addColumn('created_at')->setTitle('Created at')->isSortable()->isSearchable();
        return $table;
    }

}