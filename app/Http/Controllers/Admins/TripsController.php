<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/5/2018
 * Time: 10:30 PM
 */

namespace App\Http\Controllers\Admins;

use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Merchant;
use App\Models\Day;
use App\Models\Schedule;
use App\Models\SubRoute;
use App\Models\Trip;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\SchedulesRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TripsController extends BaseController
{

    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        $table = $this->createTripsTable();

        //return Day::with(['bookings'])->whereDate('date', '<', date('Y-m-d'))->get();
        return view('admins.pages.trips.index')->with(['tripsTable' => $table]);
    }

    /**
     * @return mixed
     */
    protected function createTripsTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.trips.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('source.name as source','destination.name as destination','buses.reg_number as reg_number',
                    'trips.price as price','days.date as date','merchants.name as name','schedules.status as status',
                    'schedules.direction as direction')
                    ->join('trips', 'trips.direction', '=', 'schedules.direction')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('locations as source', 'source.id', '=', 'trips.source')
                    ->join('locations as destination', 'destination.id', '=', 'trips.destination')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id');
            });

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('source')->setTitle('From')->isSearchable()->isSortable()->setCustomTable('trips');

        $table->addColumn('destination')->setTitle('To')->isSearchable()->isSortable()->setCustomTable('trips');

        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault()->setCustomTable('days');

        $table->addColumn('name')->setTitle('Company')->isSortable()->isSearchable()->setCustomTable('merchants');

        $table->addColumn('reg_number')->setTitle('Bus number')->isSortable()->isSearchable()->setCustomTable('buses');

        $table->addColumn('price')->setTitle('Price')->isSearchable()->isSortable()->setCustomTable('trips');

        $table->addColumn('direction')->setTitle('Direction')->setCustomTable('schedules')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['direction'] == 'GO'?'<div class="label label-success"> Going </div>':'<div class="label label-info"> Return </div>';
        });

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['status']?'<div class="label label-success"> Active </div>':'<div class="label label-danger"> Inactive </div>';
        });

        return $table;
    }
}