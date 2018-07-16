<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/21/2018
 * Time: 1:58 AM
 */

namespace App\Http\Controllers\Admins\Buses;


use App\Http\Controllers\Admins\BaseController;
use App\Http\Controllers\Buses\BusSchedulingViewParams;
use App\Repositories\Admin\Buses\BusScheduleRepository;
use App\Repositories\Admin\BusRepository;
use Illuminate\Http\Request;

class BusScheduleController extends BaseController
{
    use BusSchedulingViewParams;

    protected $busRepository;
    protected $scheduleRepository;

    public function __construct(BusRepository $busRepository, BusScheduleRepository $schedulingRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->scheduleRepository = $schedulingRepository;
    }

    /**
     * @param Request $request
     * @param $busId
     * @return $this
     */
    public function index(Request $request, $busId){

        $bus = $this->busRepository->with(['route','trips','trips.to','trips.from'])->findWithoutFail($busId);

        return view('admins.pages.bus_schedules.index')->with($this->getIndexScheduleParams($bus));
    }

}