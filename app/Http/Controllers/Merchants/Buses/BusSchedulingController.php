<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/10/2018
 * Time: 12:42 PM
 */

namespace App\Http\Controllers\Merchants\Buses;

use App\Http\Controllers\Buses\BusSchedulingViewParams;
use App\Http\Controllers\Merchants\BaseController;
use App\Http\Requests\Merchant\Buses\BusSchedulingRequest;
use App\Jobs\Schedules\AssignSchedule;
use App\Repositories\Merchant\Buses\BusRepository;
use App\Repositories\Merchant\Buses\BusSchedulingRepository;
use App\Services\BusesRoutes\BusRouteManager;
use App\Services\Schedules\ScheduleManager;
use Illuminate\Http\Request;

class BusSchedulingController extends BaseController
{

    use BusSchedulingViewParams;

    protected $busRepository;
    protected $scheduleManager;


    public function __construct(BusRepository $busRepository, ScheduleManager $scheduleManager)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->scheduleManager = $scheduleManager;
    }

    /**
     * @param Request $request
     * @param $busId
     * @return $this
     */
    public function create(Request $request, $busId){

        $bus = $this->busRepository->with(['route','trips','trips.to','trips.from'])->findWithoutFail($busId);

        return view('merchants.pages.schedules.create')->with($this->getCreateScheduleParams($bus));
    }

    public function store(BusSchedulingRequest $request, $busId){

        //$this->schedulingRepository->createSchedules($request,$busId);
        $input = $request->all();

        if ($request->has('direction')){
            $input['direction'] = 'GO';
        } else {
            $input['direction'] = 'RETURN';
        }
        $report = array();
        foreach ($input[ 'trip_dates'] as $key=>$date){
            $report[$date] = $this->scheduleManager->assignSchedule($this->busRepository->findWithoutFail($busId),$date,$input['direction']);
            //$this->dispatch(new AssignSchedule(['date'=>$date,'direction'=> $input['direction']], $busId));
        }

        return json_encode($report);
        //return redirect(route('merchant.buses.schedules.create', $busId));
    }

    /**
     * @param Request $request
     * @param $busId
     * @return array
     */
    public function busSchedules(Request $request, $busId){

        $bus = $this->busRepository->with(['route'])->findWithoutFail($busId);

        return $this->getBusSchedule($bus,$request->all()['direction']);
    }
}