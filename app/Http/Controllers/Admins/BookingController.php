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

        $merchantArray = Merchant::getMerchantsArray();

        $request->flash();

        if ($request->filled('date')){
            $this->conditions[Day::COLUMN_DATE] = $request['date'];
        } /*else {
            $now = new \DateTime();
            $this->conditions[Day::COLUMN_DATE] = $now->format('Y-m-d');
        }*/

        if ($request->filled('merchant_id')){
            if($request['merchant_id'] > 0){
                $this->conditions['merchants.id'] = $request['merchant_id'];
            }
        }

        if ($request->filled('status')){
            if(! $request['status'] == 0){
                $this->conditions['bookings.status'] = $request['status'];
            }
        }

        $table= app(TableList::class)
            ->setModel(Booking::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias'=>'admin.bookings.index','parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select([
                    Booking::FIRST_NAME.' as '.Booking::FIRST_NAME,
                    Booking::LAST_NAME.' as '.Booking::LAST_NAME,
                    Booking::EMAIL.' as '.Booking::EMAIL,
                    Booking::STATUS.' as '.Booking::STATUS,
                    SubRoute::SOURCE,'A.name as '.SubRoute::SOURCE,'B.name as '.SubRoute::DESTINATION,
                    SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,Day::DATE.' as '.Day::DATE,Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,
                    SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME,Merchant::NAME .' as '.Merchant::NAME])
                    ->join(Schedule::TABLE, Schedule::ID, '=', Booking::SCHEDULE_ID)
                    ->join(Day::TABLE, Day::ID, '=', Schedule::DAY_ID)
                    ->join(SubRoute::TABLE, SubRoute::ID, '=', Booking::SUB_ROUTE_ID)
                    ->join(BusRoute::TABLE, BusRoute::ID, '=', SubRoute::BUS_ROUTE_ID)
                    ->join(Bus::TABLE, Bus::ID, '=', BusRoute::BUS_ID)
                    ->join(Merchant::TABLE, Merchant::ID, '=', Bus::MERCHANT_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });

        $table->addColumn(Day::COLUMN_DATE)->setTitle('Date')->isSortable()->isSearchable()->setCustomTable(Day::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Day::DATE];
            });

        $table->addColumn(Booking::COLUMN_FIRST_NAME)->setTitle('First name')->isSortable()->isSearchable()->sortByDefault()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Booking::FIRST_NAME];
            });
        $table->addColumn(Booking::COLUMN_EMAIL)->setTitle('Email')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Booking::EMAIL];
            });

        $table->addColumn()->setTitle('From')->setCustomTable(Day::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::SOURCE];
            });
        $table->addColumn()->setTitle('To')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::DESTINATION];
            });

        $table->addColumn()->setTitle('Bus')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Merchant::NAME].'<br>'.'('.$entity[Bus::REG_NUMBER].')';
            });

        $table->addColumn('status')->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                if ($entity[Booking::STATUS] == Booking::$STATUS_PENDING){
                    return "<span class='label label-warning'>Pending </span> ";
                } else if ($entity[Booking::STATUS] == Booking::$STATUS_CONFIRMED){
                    return "<span class='label label-warning'>Confirmed </span>";
                } else{
                    return "<span class='label label-danger'>Cancelled </span>";
                }
            });

        return view('admins.pages.bookings.index')->with(['admin'=>auth()->user(),
            'table'=>$table,'merchants'=>$merchantArray]);;
    }

}