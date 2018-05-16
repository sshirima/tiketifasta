<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/18/2018
 * Time: 7:23 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\ReassignBusRequest;
use App\Models\ApprovalComment;
use App\Models\ApprovalRequest;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\ReassignBus;
use App\Repositories\Merchant\BusRepository;
use Illuminate\Http\Request;

class ReassignBusController extends BaseController
{
    private $busRepository;
    private $dailyTimetableId;

    public function __construct(BusRepository $busRepository)
    {
        $this->middleware('auth:merchant');
        $this->busRepository = $busRepository;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getAvailableBuses(Request $request, $dailyTimetable){

        $request['seatsCount'] = count($dailyTimetable->bookings);
        $request['busClass'] = $dailyTimetable->busRoute->bus->class;
        $request['operationDayId'] = $dailyTimetable->operation_day_id;
        $request['busId'] = $dailyTimetable->busRoute->bus->id;
        //Same route, bookings less than available seats
        $table = $this->busRepository->reassignableBuses($request);

        $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param Request $request
     * @param $busId
     * @param $scheduleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showReplacement(Request $request,$busId, $scheduleId){
        $dailyTimetable = $this->getSchedule($scheduleId);

        $table = $this->getAvailableBuses($request, $dailyTimetable);

        $this->dailyTimetableId = $scheduleId;

        return view('merchants.pages.out_of_service.reassign',
            ['merchant'=>auth()->user(),'table'=>$table,'bus'=>$dailyTimetable->busRoute->bus,'dailyTimetable'=>$dailyTimetable]);
    }

    /**
     * @param Request $request
     * @param $busId
     * @param $scheduleId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function confirmReassign(Request $request, $busId,$scheduleId){

        $dailyTimetable = $this->getSchedule($scheduleId);

        $reassignedBus = Bus::with(['merchant','busType'])->find($request['reassigned_bus_id']);

        return view('merchants.pages.out_of_service.reassign_confirm',
            ['merchant'=>auth()->user(),'bus'=>$dailyTimetable->busRoute->bus,'reassignedBus'=>$reassignedBus,'dailyTimetable'=>$dailyTimetable]);
    }

    /**
     * @param ReassignBusRequest $request
     * @param $busId
     * @param $scheduleId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reassign(ReassignBusRequest $request, $busId, $scheduleId){
        $dailyTimetable = $this->getSchedule($scheduleId);

        //Create ApprovalRequest model
        $approvalRequest = ApprovalRequest::updateOrCreate([
            'stage'=>ApprovalRequest::$STAGE_ONE,
            'type'=>ApprovalRequest::$TYPE_SCHEDULE_REASSIGNMENT,
            'status'=>ApprovalRequest::$STATUS_PENDING,
        ]);
        if (empty($approvalRequest)){
            redirect()->back()->withErrors(['message'=>'Re-assignment failed: Could not create [ApprovalRequest] model']);
        }
        //Create ReassignBus model
        $reassignBus = ReassignBus::updateOrCreate([
            'daily_timetable_id'=>$request['old_schedule_id'],
            'reassigned_bus_id'=>$request['reassigned_bus_id'],
            'approval_request_id'=>$approvalRequest->id,
        ]);
        if (empty($reassignBus)){
            redirect()->back()->withErrors(['message'=>'Re-assignment failed: Could not create [ReassignBus] model']);
        }
        //Create ApprovalComment model
        $approvalComment = ApprovalComment::updateOrCreate([
            'approval_request_id'=>$approvalRequest->id,
            'approval_stage'=>ApprovalRequest::$STAGE_ONE,
            'content'=>$request['reassign_comment'],
        ]);
        if (empty($approvalComment)){
            redirect()->back()->withErrors(['message'=>'Re-assignment failed: Could not create [ApprovalComment] model']);
        }
        //Set daily timetable as disabled
        $dailyTimetable->status = 0;
        $dailyTimetable->update();

        return redirect()->back()->with(['successMessage'=>'Re-assignment successful: The request has been sent for approval']);
    }

    /**
     * @param $scheduleId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function getSchedule($scheduleId)
    {
        $dailyTimetable = Schedule::with(['busRoute:id,bus_id',
            'busRoute.bus:id,reg_number,class,bustype_id',
            'busRoute.bus.busType:id,seats', 'bookings'])->select(['id', 'bus_route_id', 'operation_day_id'])->find($scheduleId);
        return $dailyTimetable;
    }

    /**
     * @param $table
     */
    public function setTableColumns($table): void
    {
        $table->addColumn('reg_number')->setTitle('Bus reg#')->sortByDefault()->isSortable()->isSearchable()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->reg_number;
            });
        $table->addColumn()->setTitle('Seats counts')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->seat_count;
            });
        $table->addColumn()->setTitle('Class')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->class;
            });

        $table->addColumn()->setTitle(' ')
            ->isCustomHtmlElement(function ($entity, $column) {
                return '<form method="GET" action="'.\route('merchant.buses.oos.reassign.select',[$entity->bus_id, $this->dailyTimetableId]).'" accept-charset="UTF-8">
                            <input name="_token" type="hidden" value="'.csrf_token().'">
                            <input name="reassigned_bus_id" type="hidden" value="'.$entity->bus_id.'">
                            <input name="old_schedule_id" type="hidden" value="'.$this->dailyTimetableId.'">
                            <button type="submit" class="btn btn-primary">Select</button>
                        </form>';
            });
    }
}