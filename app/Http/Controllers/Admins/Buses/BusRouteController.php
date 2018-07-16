<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/21/2018
 * Time: 1:57 AM
 */

namespace App\Http\Controllers\Admins\Buses;


use App\Http\Controllers\Admins\BaseController;
use App\Http\Controllers\Buses\BusRouteBaseController;
use App\Repositories\Admin\Buses\BusTripRepository;
use App\Repositories\Admin\BusRepository;
use Illuminate\Http\Request;

class BusRouteController extends BaseController
{

    use BusRouteBaseController;

    protected $busTripRepository;

    public function __construct(BusRepository $busRepository, BusTripRepository $tripRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->busTripRepository = $tripRepository;
    }

    public function showRoute(Request $request, $id){
        $bus = $this->busRepository->findWithoutFail($id);

        return view('admins.pages.bus_route.show')->with($this->getAssignRouteParams($bus));
    }
}