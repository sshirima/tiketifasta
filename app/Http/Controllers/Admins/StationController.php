<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\CreateStationRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Repositories\StationsRepository;
use Illuminate\Http\Request;

class StationController extends BaseController
{
    private $stationsRepository;

    /**
     * StationController constructor.
     * @param StationsRepository $stationsRepository
     */
    public function __construct(StationsRepository $stationsRepository)
    {
        parent::__construct();
        $this->stationsRepository = $stationsRepository;
    }

    /**
     * @return $this
     */
    public function index(){
        $table = $this->stationsRepository->getStationsTable();
        return view('admins.pages.stations.index')->with(['table'=>$table]);
    }

    /**
     * @return $this
     */
    public function create(){
        return view('admins.pages.stations.create')->with($this->stationsRepository->getCreateNewStationPreInputs());
    }

    /**
     * @param CreateStationRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CreateStationRequest $request){
        $this->stationsRepository->createNewStation($request->all());
        return redirect(route('admin.stations.index'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this
     */
    public function edit(Request $request, $id){
        return view('admins.pages.stations.edit')->with($this->stationsRepository->getEditStationInputs($id));
    }

    public function update(UpdateStationRequest $request, $id){
        $this->stationsRepository->updateStation($request->all(), $id);
        return redirect(route('admin.stations.index'));
    }

    public function destroy (Request $request, $id){
        $this->stationsRepository->delete($id);
        return redirect(route('admin.stations.index'));
    }

}