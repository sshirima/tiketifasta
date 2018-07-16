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
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingController extends BaseController
{
    public $conditions = array();

    public function __construct()
    {
        parent::__construct();
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
                'index' => ['alias' => 'merchant.bookings.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('bookings.id as id','days.date as date', 'bookings.firstname as firstname',
                    'bookings.lastname as lastname','bookings.email as email','source.name as source','destination.name as destination',
                    'buses.reg_number as reg_number','trips.price as price','bookings.status as status',
                    'bookings.payment_ref as payment_ref','merchants.name as merchant_name')
                    ->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('trips', 'trips.id', '=', 'bookings.trip_id')
                    ->join('locations as source', 'source.id', '=', 'trips.source')
                    ->join('locations as destination', 'destination.id', '=', 'trips.destination')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
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
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault()->setCustomTable('days');
        $table->addColumn('payment_ref')->setTitle('Reference #')->isSearchable()->isSortable();
        $table->addColumn('firstname')->setTitle('First name')->isSearchable()->isSortable();
        $table->addColumn('lastname')->setTitle('Last name')->isSearchable()->isSortable();
        $table->addColumn('email')->setTitle('Email')->isSearchable()->isSortable();
        $table->addColumn('reg_number')->setTitle('Email')->setCustomTable('buses')->isSearchable()->isSortable()
            ->isCustomHtmlElement(function ($entity, $column){
                return $entity['reg_number'].'<br>'.'('.$entity['merchant_name'].')';
            });
        $table->addColumn('source')->setTitle('From')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('destination')->setTitle('To')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('price')->setTitle('Price')->isSearchable()->isSortable()->setCustomTable('trips');
        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status']== Booking::$STATUS_CONFIRMED?
                '<div class="label label-success">'.'Paid'.'</div>':'<div class="label label-success">'.$column['status'].'</div>';
        });
        return $table;
    }

}