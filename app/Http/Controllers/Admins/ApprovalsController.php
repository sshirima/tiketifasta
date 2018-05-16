<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/22/2018
 * Time: 1:52 PM
 */

namespace App\Http\Controllers\Admins;


use App\Models\ApprovalComment;
use App\Models\ApprovalRequest;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Schedule;
use App\Models\Merchant;
use App\Models\ReassignBus;
use App\Repositories\Admin\BookingRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Merchant\TimetableRepository;
use Illuminate\Http\Request;

class ApprovalsController extends BaseController
{
    public $conditions = array();
    public $timetableRepo;
    public $bookingRepo;

    /**
     * ApprovalsController constructor.
     * @param TimetableRepository $scheduleRepository
     * @param BookingRepository $bookingRepository
     */
    public function __construct(TimetableRepository $scheduleRepository, BookingRepository $bookingRepository)
    {
        $this->middleware('auth:admin');
        $this->timetableRepo = $scheduleRepository;
        $this->bookingRepo = $bookingRepository;
    }

    /**
     * @return $this
     */
    public function index(){
        return view('admins.pages.approvals.index')->with(['admin'=>auth()->user()]);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function busRoutes(){
        return redirect(route('admin.bus-route.inactive.show'));
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function reassignedSchedules(Request $request){
        $merchantArray = Merchant::getMerchantsArray();
        $routes = RouteRepository::getRouteArray();
        $request->flash();

        $request['reassigned_buses_status'] = 0;

        $table = $this->timetableRepo->reassignedSchedules($request);

        $this->setTimetableColumns($table);

        return view('admins.pages.approvals.reassign_schedule')->with(['admin'=>auth()->user(),
            'table'=>$table,'routes'=>$routes,'merchants'=>$merchantArray]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showReassignedSchedule($id){

        $rescheduled = ReassignBus::find($id);

        $dailyTimetable = $this->timetableRepo->getScheduleWithId($rescheduled->daily_timetable_id);

        $newBus = Bus::with(['merchant','busType','reassignedSchedule','reassignedSchedule.approvalRequest'])->find($rescheduled->reassigned_bus_id);

        return view('admins.pages.approvals.reassign_schedule_show',
            ['admin'=>auth()->user(),'bus'=>$dailyTimetable->busRoute->bus,
                'reassignedBus'=>$newBus,'dailyTimetable'=>$dailyTimetable]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showBookings(Request $request, $id)
    {
        $rescheduled = ReassignBus::find($id);

        $dailyTimetable = $this->timetableRepo->getScheduleWithId($rescheduled->daily_timetable_id);

        $newBus = Bus::with(['merchant','busType','reassignedSchedule','reassignedSchedule.approvalRequest'])->find($rescheduled->reassigned_bus_id);

        $table = $this->dailyBookings($request);

        return view('admins.pages.approvals.reassign_schedule_confirm',
            ['admin'=>auth()->user(),'bus'=>$dailyTimetable->busRoute->bus,
                'reassignedBus'=>$newBus,'bookingTable'=>$table]);
        //
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, $id){
        /*$reassignedBus = ReassignBus::with(['approvalRequest'])->find($id);

        $cancelledSchedule = DailyTimetable::with(['busRoute','bookings'])->find($reassignedBus->daily_timetable_id);
        $approvalRequest = $reassignedBus->approvalRequest;

        //Create bus route
        $newBusRoute = BusRoute::create([
            'bus_id'=>$reassignedBus->reassigned_bus_id,
            'route_id'=>$cancelledSchedule->busRoute->route_id,
            'start_time'=>$cancelledSchedule->busRoute->start_time,
            'end_time'=>$cancelledSchedule->busRoute->end_time,
        ]);

        if(empty($newBusRoute)){
            redirect()->back()->withErrors(['message'=>'Confirmation failed: failed to create new bus route']);
        }
        //Create new dailyTimetable
        $newSchedule = DailyTimetable::create([
            'operation_day_id'=>$cancelledSchedule->operation_day_id,
            'bus_route_id'=>$newBusRoute->id,
            'timetable_id'=>$cancelledSchedule->timetable_id,
            'status'=>1,
        ]);

        if(empty($newSchedule)){
            redirect()->back()->withErrors(['message'=>'Confirmation failed: failed to create new schedule']);
        }
        //Assign bookings to the new dailytimetables
        $bookings = Booking::where(['daily_timetable_id'=>$cancelledSchedule->id])->update(['daily_timetable_id'=>$newSchedule->id]);

        if(count($bookings) == 0){
            redirect()->back()->withErrors(['message'=>'Confirmation failed: failed to update the bookings']);
        }
        //Assign bus seat to the bookings
        $seats = $newBusRoute->bus()->first()->seats();

        if(count($seats) == 0){
            redirect()->back()->withErrors(['message'=>'Confirmation failed: failed to update the bookings']);
        }
        $i=0;
        foreach ($bookings as $booking){
            $booking->seat_id = $seats[$i]->id;
            $booking->update();
            $i++;
        }
        //Mark daily timetable as disabled
        $cancelledSchedule->status = 0;
        $cancelledSchedule->update();

        //Send SMS to the affected customers/Email to customer

        //Mark approval request as approved and stage two
        $approvalRequest->update([
            'stage'=>ApprovalRequest::$STAGE_TWO,
            'status'=>ApprovalRequest::$STATUS_APPROVED,
        ]);
        //Mark reassignBuses as status approved
        $reassignedBus->status = 1;
        $reassignedBus->update();

        //Save approval comment
        if($request->filled('reassign_comment')){
            $approvalComment = ApprovalComment::updateOrCreate([
                'approval_request_id'=>$approvalRequest->id,
                'approval_stage'=>ApprovalRequest::$STAGE_TWO,
                'content'=>$request['reassign_comment'],
            ]);
        }*/

        return redirect()->back()->with([
            'messageSuccess'=>'Bus reassignment has been done successful!',
            'messageSMS'=>'SMS has been sent to the customers to inform them on the change of the bus!',
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function dailyBookings(Request $request)
    {
        $table = $this->bookingRepo->dailyBookings($request);

        $this->setBookingTable($table);

        return $table;
    }

    public function setBookingTable($table){

        $table->addColumn('reg_number')->setTitle('Bus#')->isSortable()->sortByDefault()->isSearchable()->setCustomTable('buses');

        $table->addColumn()->setTitle('Client')->isSortable()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->firstname.' '.$entity->lastname.'</br>'.'('.$entity->email.')';
            });
        $table->addColumn()->setTitle('Phone number')->isSortable()->setCustomTable('operation_days')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->phonenumber;
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

    /**
     * @param $table
     */
    public function setTimetableColumns($table): void
    {
        $table->addColumn('reg_number')->setTitle('Company/Bus')->isSortable()->isSearchable()->sortByDefault()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->merchant_name . '</br>' . '(' . $entity->reg_number . ')';
            });
        $table->addColumn()->setTitle('Route/Trip')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->route_name . '</br>' . '(' . $entity->location_start . ' to ' . $entity->location_end . ')';
            });
        $table->addColumn()->setTitle('Date/Time')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->date . '</br>' . '(' . $entity->start_time . ' to ' . $entity->arrival_time . ')';
            });
        $table->addColumn()->setTitle('Status')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->daily_timetable_status?
                    "<span style='color: green;'>Active <i class='fas fa-check-circle'></i></span>":
                    "<span style='color: red;'>Disabled <i class='fas fa-times-circle'></i></span>"
                    ;
            });
        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {
                return '<form method="GET" action="'.\route('admin.approvals.reassigned-schedules.show',$entity->reassigned_buses_id).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <button type="submit" class="btn btn-primary">Select</button>
                        </form>';
            });
    }

}