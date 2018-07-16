<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/6/2018
 * Time: 11:51 PM
 */

namespace App\Http\Controllers\Merchants\Buses;


use App\Http\Controllers\Buses\BusPriceViewParams;
use App\Http\Controllers\Merchants\BaseController;
use App\Models\Trip;
use App\Repositories\Merchant\Buses\BusRepository;
use App\Repositories\Merchant\Buses\TripRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Response;

class TripPriceController extends BaseController
{
    use BusPriceViewParams;

    protected $busRepository;
    protected $tripRepository;

    protected $rule_save_price =
        [
            'price-value' => 'required|min:1',
        ];

    /**
     * TripPriceController constructor.
     * @param BusRepository $busRepository
     * @param TripRepository $tripRepository
     */
    public function __construct(BusRepository $busRepository, TripRepository $tripRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->tripRepository = $tripRepository;
    }

    /**
     * @param Request $request
     * @param $busId
     * @return $this
     */
    public function assignPrice(Request $request, $busId){

        $bus = $this->busRepository->with(['route','trips','trips.from','trips.to'])->findWithoutFail($busId);

        return view('merchants.pages.bus_route_price.assign')->with(['bus'=>$bus]);

    }

    /**
     * @param Request $request
     * @param $busId
     * @param $tripId
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveTripPrice(Request $request, $busId, $tripId){
        $validator = Validator::make(Input::all(), $this->rule_save_price);

        if ($validator->fails()){
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }else{
            $trip = $this->tripRepository->findWithoutFail($tripId);
            $trip[Trip::COLUMN_PRICE] = $request->all()['price-value'];
            $trip->update();
            return response()->json($trip);
        }
    }

}