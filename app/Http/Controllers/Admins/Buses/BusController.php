<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 12:49 PM
 */

namespace App\Http\Controllers\Admins\Buses;


use App\Http\Controllers\Admins\BaseController;
use App\Http\Controllers\Buses\BusBaseController;
use App\Http\Requests\Admin\Buses\CreateBusRequest;
use App\Http\Requests\Admin\Buses\UpdateBusRequest;
use App\Models\Bus;
use App\Models\Merchant;
use App\Repositories\Admin\Buses\BusRepository;
use App\Repositories\Admin\Buses\BusSeatRepository;

class BusController extends BaseController
{
    use BusBaseController;

    protected $seatRepository;

    /**
     * BusController constructor.
     * @param BusRepository $busRepository
     * @param BusSeatRepository $seatRepository
     */
    public function __construct(BusRepository $busRepository, BusSeatRepository $seatRepository)
    {
        parent::__construct();
        $this->busRepository = $busRepository;
        $this->seatRepository = $seatRepository;
        $this->indexPage = 'admins.pages.buses.index';
    }

    public function create(){
        return view('admins.pages.buses.create')->with($this->getCreateParams());
    }

    /**
     * @param CreateBusRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateBusRequest $request)
    {
        $bus = $this->busRepository->create($request->all());

        $this->busRepository->createBusSeats($bus,$this->seatRepository);

        $this->createFlashResponse($bus,__('admin_page_buses.create_success'),__('admin_page_buses.create_fail'));

        return redirect(route('admin.buses.index'));
    }

    public function edit($id){
        $bus = $this->busRepository->findWithoutFail($id);

        return view('admins.pages.buses.create')->with($this->getEditParams($bus));
    }

    /**
     * @param UpdateBusRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(UpdateBusRequest $request, $id){

        $this->busRepository->update($request->all(),$id);

        return redirect(route('admin.buses.index'));
    }



}