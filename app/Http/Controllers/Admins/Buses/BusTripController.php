<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/21/2018
 * Time: 1:58 AM
 */

namespace App\Http\Controllers\Admins\Buses;


use App\Http\Controllers\Admins\BaseController;
use App\Http\Controllers\Buses\BusPriceViewParams;
use Illuminate\Http\Request;
use App\Repositories\Merchant\Buses\BusRepository;

class BusTripController extends BaseController
{
    protected $busRepository;

    public function __construct(BusRepository $busRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
    }

    /**
     * @param Request $request
     * @param $busId
     * @return $this
     */
    public function tripPrice(Request $request, $busId){

        $bus = $this->busRepository->with(['route','trips','trips.from','trips.to'])->findWithoutFail($busId);

        return view('admins.pages.buses.prices')->with(['bus'=>$bus]);

    }

}