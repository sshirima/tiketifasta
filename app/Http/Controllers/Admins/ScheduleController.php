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
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\SchedulesRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class ScheduleController extends BaseController
{

    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        $table = $this->createSchedulesTable();

        return view('admins.pages.schedules.index')->with(['schedulesTable' => $table]);
    }

    /**
     * @return mixed
     */
    protected function createSchedulesTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.schedules.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('schedules.id as id','buses.reg_number as reg_number','schedules.status as status',
                    'days.date as date','merchants.name as name','routes.route_name as route_name' ,'schedules.direction as direction')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('routes', 'routes.id', '=', 'buses.route_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
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
        $table->addColumn('date')->setTitle('Date')->isSortable()->isSearchable()->sortByDefault('desc')->setCustomTable('days');

        $table->addColumn('name')->setTitle('Company')->isSortable()->isSearchable()->setCustomTable('merchants');

        $table->addColumn('route_name')->setTitle('Route name')->isSortable()->isSearchable()->setCustomTable('routes');

        $table->addColumn('reg_number')->setTitle('Bus number')->isSortable()->isSearchable()->setCustomTable('buses');

        $table->addColumn('direction')->setTitle('Direction')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['direction'] == 'GO'?'<div class="label label-default"> Going </div>':'<div class="label label-info"> Return </div>';
        });

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['status']?'<div class="label label-success"> Active </div>':'<div class="label label-danger"> Inactive </div>';
        });

        return $table;
    }





}