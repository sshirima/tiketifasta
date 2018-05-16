<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/20/2018
 * Time: 5:10 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Models\Bus;
use App\Models\BusRoute;
use App\Repositories\Merchant\TimetableRepository;
use Illuminate\Http\Request;

class TimetableController extends BaseController
{
    private $timetableRepository;

    public function __construct(TimetableRepository $scheduleRepository)
    {
        $this->middleware('auth:merchant');
        $this->timetableRepository = $scheduleRepository;
    }

    public function index(Request $request){

        return view('merchants.pages.timetables.index')->with(['merchant'=>auth()->user(),
            'table'=>$this->getBusTimetables($request),'buses'=>$this->getBusesArray(),'routes'=>$this->getRouteArray()]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getBusTimetables(Request $request){

        $table = $this->timetableRepository->busTimetables($request);

        $this->setTableColumns($table);

        return $table;
    }

    public function seatView(){
        return view('merchants.pages.bus_types.seats')->with(['merchant'=>auth()->user()]);
    }

    public function edit(){}

    public function update(){}

    /**
     * @param $table
     */
    public function setTableColumns($table): void
    {
        $table->addColumn('reg_number')->setTitle('Bus reg#')->sortByDefault()->isSortable()->isSearchable()->setCustomTable('buses')
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->reg_number;
            });

        $table->addColumn()->setTitle('From')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->location_start;
            });
        $table->addColumn()->setTitle('To')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->location_end;
            });
        $table->addColumn()->setTitle('Depart')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->start_time;
            });

        $table->addColumn()->setTitle('Arrive')->isSortable()->isSearchable()
            ->isCustomHtmlElement(function ($entity, $column) {
                return $entity->arrival_time;
            });
    }

    /**
     * @return array
     */
    public function getBusesArray(): array
    {
        $routes = auth()->user()->merchant()->first()->getMerchantBusArray();

        return $routes;
    }

    /**
     * @param int $bus_id
     * @return array
     */
    public function getRouteArray($bus_id = 0): array
    {
        $busRoute = new BusRoute();
        $routes = $busRoute->getMerchantRoutesArray($bus_id, auth()->user()->merchant()->first());
        return $routes;
    }
}