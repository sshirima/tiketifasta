<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 12:47 PM
 */

namespace App\Http\Controllers\Merchants\Buses;

use App\Http\Controllers\Buses\BusRouteBaseController;
use App\Http\Controllers\Merchants\BaseController;
use App\Http\Requests\Merchant\Buses\AssignRoutesBusRequest;
use App\Models\Trip;
use App\Repositories\Merchant\Buses\BusRepository;
use App\Repositories\Merchant\Buses\TripRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Response;

class BusRoutesController extends BaseController
{
    use BusRouteBaseController;

    protected $rule_update_time =
        [
            Trip::COLUMN_ARRIVAL_TIME => 'required|date_format:"h:i A"',
            Trip::COLUMN_DEPART_TIME => 'required|date_format:"h:i A"',
        ];

    protected $busRepository;
    protected $tripRepository;

    public function __construct(BusRepository $busRepository, TripRepository $tripRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->tripRepository = $tripRepository;
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function assignRoute(Request $request, $id){

        $bus = $this->busRepository->findWithoutFail($id);

        $tripCount = $bus->busTrips()->count();

        $request[Trip::COLUMN_BUS_ID] = $id;

        if($tripCount > 0){
            $bus->tripCount = $tripCount;
        }

        return view('merchants.pages.bus_routes.assign')->with($this->getAssignRouteParams($bus));
    }

    /**
     * @param AssignRoutesBusRequest $request
     * @param $id
     * @return array
     */
    public function saveBusRoute(AssignRoutesBusRequest $request, $id){

        $sortedTrips = $this->tripRepository->analyseTrips($request->all()['trips']);

        $this->tripRepository->createOrUpdate($sortedTrips, $id);

        $this->busRepository->assignRoute($request, $id);

        return redirect(route('merchant.buses.assign_routes',$id));
    }


    /**
     * @param Request $request
     * @param $tripId
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTripTime(Request $request, $busId, $tripId){
        $validator = Validator::make(Input::all(), $this->rule_update_time);

        if ($validator->fails()){
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }else{
            $trip = $this->tripRepository->updateTripTime($request, $tripId);
            return response()->json($trip);
        }
    }

}