<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Models\Booking;
use App\Models\Merchant;
use App\Repositories\Admin\BookingRepository;
use Illuminate\Http\Request;

class BookingController extends BaseController
{

    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->middleware('auth:merchant');
        $this->bookingRepository = $bookingRepository;
    }

    public function index(Request $request){
        $staff = auth()->user();
        $buses = Merchant::merchantBusesArray($staff);

        $request->flash();

        $table = $this->dailyBookings($request);

        return view('merchants.pages.bookings.index')->with(['merchant'=>$staff,
            'table'=>$table,'buses'=>$buses]);

        //return $buses;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dailyBookings(Request $request)
    {
        $table = $this->bookingRepository->dailyBookings($request);

        $this->setBookingTable($table);

        return $table;
    }

    public function setBookingTable($table){

        $table->addColumn('reg_number')->setTitle('Bus#')->isSortable()->sortByDefault()->isSearchable()->setCustomTable('buses');

        $table->addColumn('date')->setTitle('Client')->isSortable()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->firstname.' '.$entity->lastname.'</br>'.'('.$entity->email.')';
            });
        $table->addColumn('email')->setTitle('Date/Time')->isSortable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->date.'</br>'.'('.$entity->start_time.'-'.$entity->arrival_time.')';
            });
        $table->addColumn()->setTitle('Route')->isSortable()
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
    }
}