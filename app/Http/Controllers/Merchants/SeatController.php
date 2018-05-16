<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/13/2018
 * Time: 7:56 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\CreateLocationRequest;
use App\Models\Location;
use App\Models\Seat;
use App\Repositories\Merchant\BusRepository;
use App\Repositories\Merchant\LocationRepository;
use App\Repositories\Merchant\SeatRepository;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class SeatController extends BaseController
{
    private $seatRepository;
    private $busRepo;
    private $busId;

    public function __construct(SeatRepository $seatRepository, BusRepository $busRepository)
    {
        $this->middleware('auth:merchant');
        $this->seatRepository = $seatRepository;
        $this->busRepo = $busRepository;
    }

    public function index($id){
        $this->busId = $id;
        $bus = $this->busRepo->findWithoutFail($id);

        $seat_table= app(TableList::class)
            ->setModel(Seat::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
            'index' => ['alias'=>'merchant.bus.seats','parameters' => ['bus_id']]
        ])->addQueryInstructions(function ($query) {
                $query->select(['seats.seat_name','seats.type', 'seats.status'])
                    ->where(['seats.bus_id'=>$this->busId]);
            });

        $seat_table->addColumn('seat_name')->setTitle('Seat name')->sortByDefault()->isSearchable();
        $seat_table->addColumn('type')->setTitle('Seat type');
        $seat_table->addColumn('status')->setTitle('Status');

        return view('merchants.pages.seats.index')->with([
            'merchant'=>auth()->user(),
            'table'=>$seat_table,
            'bus'=>$bus
        ]);
    }

    public function create($bus_id){
        $bus = $this->busRepo->findWithoutFail($bus_id);
        $bus->withCount('seats')->get();
        if ($bus->seats()->count() == 0){
            Seat::createBusSeats($bus->id,$bus->bustype_id, $this->seatRepository);
        }
        return redirect(route('merchant.bus.seats',$bus_id));
    }

    public function tickets($bus_id){

    }

    public function edit(){

    }

    public function update(){

    }


}