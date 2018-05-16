<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/25/2018
 * Time: 4:19 PM
 */

namespace App\Http\Controllers\Users;


use App\Http\Requests\Users\CreateBookingRequest;
use App\Http\Requests\Users\PrepareBookingRequest;
use App\Http\Requests\Users\SelectBusRequest;
use App\Http\Requests\Users\SelectSeatRequest;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Schedule;
use App\Models\Location;
use App\Models\Day;
use App\Models\ScheduleSeat;
use App\Models\Seat;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Models\Timetables;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\SchedulesRepository;
use App\Repositories\Admin\SubRouteRepository;
use App\Repositories\Merchant\BusRepository;
use App\Repositories\Merchant\TimetableRepository;
use App\Repositories\User\BookingRepository;
use Illuminate\Http\Request;

class BookingController extends BaseController
{
    const PARAM_SOURCE = 'sources';
    const PARAM_SEATS = 'seats';
    const PARAM_SEAT_ARRANGEMENT = 'seatArrangement';
    const PARAM_BUS = 'bus';
    const PARAM_TICKET_PRICE = 'ticketPrice';
    const PARAM_SCHEDULE = 'schedule';
    const PARAM_DESTINATION = 'destinations';

    const VIEW_INDEX = 'users.pages.bookings.select_schedule';
    const VIEW_SELECT_BUS = 'users.pages.bookings.select_bus';
    const VIEW_SELECT_SEATS = 'users.pages.bookings.select_seats';
    const VIEW_BOOKING_DETAILS = 'users.pages.bookings.booking_details';

    private $subRouteRepo;
    private $scheduleRepo;
    private $bookingRepo;

    const RETURN_COLUMNS = [Merchant::NAME.' as '.Merchant::NAME, Bus::REG_NUMBER.' as '.Bus::REG_NUMBER,
        SubRoute::ARRIVAL_TIME.' as '.SubRoute::ARRIVAL_TIME,SubRoute::DEPART_TIME.' as '.SubRoute::DEPART_TIME,Schedule::ID.' as '.Schedule::ID,
        Bus::ID.' as '.Bus::ID, TicketPrice::PRICE.' as '.TicketPrice::PRICE, SubRoute::ID.' as '.SubRoute::ID];

    public function __construct(SubRouteRepository $subRouteRepository, SchedulesRepository $scheduleRepository, BookingRepository $bookingRepository)
    {
        $this->subRouteRepo = $subRouteRepository;
        $this->scheduleRepo = $scheduleRepository;
        $this->bookingRepo = $bookingRepository;
    }

    public function selectTimetable()
    {
        $this->viewData[self::PARAM_SOURCE]=  $this->subRouteRepo->sourceLocations();

        $this->viewData[self::PARAM_DESTINATION]= $this->subRouteRepo->destinationLocations();

        return view(self::VIEW_INDEX)->with($this->viewData);
    }

    public function selectBus(SelectBusRequest $request)
    {
        $input = $request->all();
        $journey = array();

        if ($input['trip_type'] == 'round_trip') {
            $returnDate = $input['return_date'];
        }
        $travellingDate = new \DateTime($input['departing_date']);
        $now = new \DateTime();

        /*if($travellingDate->format('Y-m-d') < $now->format('Y-m-d')){
            return redirect()->back()->withErrors(['message'=>'Travelling date should be greater than or equal to Today: '.$now->format('Y-m-d')]);
        }*/

        $startLocation = $input['start_location'];
        $destination = $input['destination'];

        $table = $this->getBusSchedules($request);

        $journey['date'] = $input['departing_date'];
        $journey['source'] = Location::find($startLocation);
        $journey['destination'] = Location::find($destination);

        //return $buses;
        return view('users.pages.bookings.select_bus')->with(['table' => $table, 'journey' => $journey]);
    }

    private $subRouteId;

    public function selectSeat(SelectSeatRequest $request, $scheduleId, $subRouteId)
    {
        $this->subRouteId = $subRouteId;

        $schedule = $this->getSchedule($scheduleId);

        $this->viewData[self::PARAM_SEATS]= $schedule->busRoute->bus->seatArray(Seat::seatArrangementArray($schedule->busRoute->bus->busType->seat_arrangement));

        $this->viewData[self::PARAM_BUS]= $schedule->busRoute->bus;

        $this->viewData[self::PARAM_SCHEDULE]= $schedule;

        return view(self::VIEW_SELECT_SEATS)->with($this->viewData);
    }

    public function prepareBooking(PrepareBookingRequest $request, $scheduleId, $subRouteId)
    {
        $this->subRouteId = $subRouteId;

        $input = $request->all();

        $schedule = Schedule::with(['busRoute.bus','busRoute.bus.merchant', 'day','busRoute.subRoutes'=>function($query){
            $query->where([SubRoute::COLUMN_ID=>$this->subRouteId]);
        },'busRoute.subRoutes.ticketPrice','busRoute.subRoutes.sourceLocation','busRoute.subRoutes.destinationLocation'])->find($scheduleId);;

        $schedule->seat = $input['seat'];

        $this->viewData[self::PARAM_SCHEDULE]= $schedule;

        return view(self::VIEW_BOOKING_DETAILS)->with($this->viewData);
    }

    private function getSchedule($scheduleId){
        return Schedule::with(['busRoute.bus', 'busRoute.bus.busType','busRoute.subRoutes'=>function($query){
            $query->where([SubRoute::COLUMN_ID=>$this->subRouteId]);
        },'busRoute.subRoutes.ticketPrice'])->find($scheduleId);
    }

    public function storeBookingDetails(CreateBookingRequest $request, $scheduleId, $subRouteId)
    {
        $input = $request->all();

        $schedule = Schedule::with(['busRoute.bus:id'])->find($scheduleId);

        $seat = Seat::where(['seat_name'=>$input['seat'],'bus_id'=>$schedule->busRoute->bus->id])->first();

        $booking = $this->bookingRepo->create([
            Booking::COLUMN_TITLE => $input[Booking::COLUMN_TITLE],
            Booking::COLUMN_FIRST_NAME => $input[Booking::COLUMN_FIRST_NAME],
            Booking::COLUMN_LAST_NAME => $input[Booking::COLUMN_LAST_NAME],
            Booking::COLUMN_PHONE_NUMBER => $input[Booking::COLUMN_PHONE_NUMBER],
            Booking::COLUMN_EMAIL => $request->has(Booking::COLUMN_EMAIL) ? $input[Booking::COLUMN_EMAIL] : null,
            Booking::COLUMN_PAYMENT => $input[Booking::COLUMN_PAYMENT],
            Booking::COLUMN_SCHEDULE_ID => $scheduleId,
            Booking::COLUMN_SUB_ROUTE_ID =>$subRouteId,
            Booking::COLUMN_SEAT_ID =>$seat->id,
        ]);

        //Save scheduled seat
        ScheduleSeat::create([
            ScheduleSeat::COLUMN_SCHEDULE_ID =>$scheduleId,
            ScheduleSeat::COLUMN_SEAT_ID =>$seat->id,
            ScheduleSeat::STATUS=>'Unavailable'
        ]);

        //Get mpesa payment account number from config file

        //Generate booking reference number

        return ' booked ';
    }

    private function getDailyTimetable($journey){
        return Schedule::select(['daily_timetables.id'])
            ->join('bus_route','bus_route.id','=','daily_timetables.bus_route_id')
            ->join('operation_days','operation_days.id','=','daily_timetables.operation_day_id')
            ->where(['operation_days.date'=>$journey->date])
            ->where(['bus_route.bus_id'=>$journey->timetable->bus_id])->first();
    }

    private function getBusSchedules(Request $request){

        $this->scheduleRepo->setTripConditions($request);

        $this->scheduleRepo->setColumns(self::RETURN_COLUMNS);

        $table = $this->scheduleRepo->getScheduledBuses();

        $this->setBusTableColumns($table);

        return $table;
    }
    private function setBusTableColumns($table): void
    {
        $table->addColumn(Merchant::COLUMN_NAME)->setTitle(__('admin_pages.page_bus_index_table_head_bus'))->isSortable()
            ->isSearchable()
            ->useForDestroyConfirmation()
            ->setCustomTable(Merchant::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Merchant::NAME];
            });
        $table->addColumn(Bus::COLUMN_REG_NUMBER)->setTitle(__('admin_pages.page_bus_index_table_head_reg_number'))
            ->isSortable()->isSearchable()->setCustomTable(Bus::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[Bus::REG_NUMBER];
            });
        $table->addColumn(SubRoute::COLUMN_DEPART_TIME)->setTitle(__('admin_pages.page_bus_index_table_head_depart_time'))
            ->isSortable()->isSearchable()->sortByDefault()
            ->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::DEPART_TIME];
            });
        $table->addColumn(SubRoute::COLUMN_ARRIVAL_TIME)->setTitle(__('admin_pages.page_bus_index_table_head_arrival_time'))
            ->isSortable()->isSearchable()->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[SubRoute::ARRIVAL_TIME];
            });

        $table->addColumn()->setTitle(__('admin_pages.page_bus_edit_sub_route_table_head_time_difference'))
            ->setCustomTable(SubRoute::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                $depart = date_create('2018-01-01 '.$entity[SubRoute::DEPART_TIME]);
                $arrival = date_create('2018-01-01 '.$entity[SubRoute::ARRIVAL_TIME]);
                return date_diff( $depart, $arrival )->h .' hour(s)';
            });

        $table->addColumn(TicketPrice::COLUMN_PRICE)->setTitle(__('admin_pages.page_bus_index_table_head_price'))
            ->isSortable()->isSearchable()->setCustomTable(TicketPrice::TABLE)
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity[TicketPrice::PRICE];
            });

        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {
                return '<form method="GET" action="'.\route('booking.select.seats',[$entity[Schedule::ID], $entity[SubRoute::ID]]).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <button type="submit" class="btn btn-success">Select</button>
                        </form>';
            });
    }
}