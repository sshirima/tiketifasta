<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/8/2018
 * Time: 7:26 PM
 */

namespace App\Http\Controllers\Admins;


use App\Models\Booking;
use App\Models\Merchant;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BookingController extends BaseController
{
    public $conditions = array();

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(Request $request){

        $merchantArray = Merchant::getMerchantsArray();

        $request->flash();

        if ($request->filled('date')){
            $this->conditions['operation_days.date'] = $request['date'];
        } else {
            $now = new \DateTime();
            $this->conditions['operation_days.date'] = $now->format('Y-m-d');
        }

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
                $query->select(['bookings.firstname','bookings.lastname','operation_days.date','bus_route.start_time',
                    'timetables.arrival_time','B.name as location_start','A.name as location_end','merchants.name as merchant_name','routes.route_name','bookings.email','buses.reg_number','bookings.status'])
                    ->join('daily_timetables','daily_timetables.id','=','bookings.daily_timetable_id')
                    ->join('operation_days','operation_days.id','=','daily_timetables.operation_day_id')
                    ->join('bus_route','bus_route.id','=','daily_timetables.bus_route_id')
                    ->join('routes','bus_route.route_id','=','routes.id')
                    ->join('timetables','timetables.id','=','daily_timetables.timetable_id')
                    ->join('buses','buses.id','=','bus_route.bus_id')
                    ->join('merchants','merchants.id','=','buses.merchant_id')
                    ->join('locations as A','A.id','=','timetables.location_id')
                    ->join('locations as B','B.id','=','routes.start_location')
                    ->where($this->conditions);
            });

        $table->addColumn('reg_number')->setTitle('Company/Bus')->isSortable()->isSearchable()->sortByDefault()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
               return $entity->merchant_name.'</br>'.'('.$entity->reg_number.')';
            });

        $table->addColumn('date')->setTitle('Client')->isSortable()->isSearchable()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->firstname.' '.$entity->lastname.'</br>'.'('.$entity->email.')';
            });
        $table->addColumn('email')->setTitle('Date/Time')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->date.'</br>'.'('.$entity->start_time.'-'.$entity->arrival_time.')';
            });
        $table->addColumn()->setTitle('Route')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->route_name.'</br>'.'('.$entity->location_start.' to '.$entity->location_end.')';
            });
        $table->addColumn('status')->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                if ($entity->status == Booking::$STATUS_PENDING){
                    return "<span style='color: orange'>Pending <i class='fas fa-spinner'></i></span> ";
                } else if ($entity->status == Booking::$STATUS_CONFIRMED){
                    return "<span style='color: green;'>Confirmed <i class='fas fa-check-circle'></i></span>";
                } else{
                    return "<span style='color: red;'>Cancelled <i class='fas fa-times-circle'></i></span>";
                }
            });

        return view('admins.pages.bookings.index')->with(['admin'=>auth()->user(),
            'table'=>$table,'merchants'=>$merchantArray]);;
    }

}