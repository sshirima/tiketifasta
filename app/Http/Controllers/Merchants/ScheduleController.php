<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 2:32 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\AssignScheduleRequest;
use App\Jobs\Schedules\AssignSchedule;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class ScheduleController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        $this->setMerchantId();

        $table = $this->createSchedulesTable();

        return view('merchants.pages.schedules.index')->with(['schedulesTable' => $table]);
    }

    public function create(Request $request){
        return 'Create schedule';
    }

    /**
     * @param AssignScheduleRequest $request
     * @param $busId
     * @return string
     */
    public function assignSchedule(AssignScheduleRequest $request, $busId){

        //$this->dispatch(new AssignSchedule($request->all(), $busId));

        return view();
    }

    /**
     * @return mixed
     */
    protected function createSchedulesTable()
    {
        $table = app(TableList::class)
            ->setModel(Schedule::class)
            ->setRowsNumber(10)
            ->setRoutes([
                'index' => ['alias' => 'merchant.schedules.index', 'parameters' => []],
                'destroy' => ['alias' => 'merchant.schedules.remove', 'parameters' => ['id']],
                'create' => ['alias' => 'merchant.schedules.create', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('schedules.id as id','buses.reg_number as reg_number','schedules.status as status',
                    'days.date as date','routes.route_name as route_name','schedules.direction as direction')
                    ->join('buses', 'buses.id', '=', 'schedules.bus_id')
                    ->join('routes', 'routes.id', '=', 'buses.route_id')
                    ->join('days', 'days.id', '=', 'schedules.day_id')
                    ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
                    ->where('merchants.id', $this->merchantId);
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
        $table->addColumn('date')->setTitle('Date')->isSearchable()->sortByDefault()->useForDestroyConfirmation()->setCustomTable('days');

        $table->addColumn('reg_number')->setTitle('Bus number')->isSortable()->isSearchable()->setCustomTable('buses');

        $table->addColumn('route_name')->setTitle('Route name')->isSortable()->isSearchable()->setCustomTable('routes');

        $table->addColumn('direction')->setTitle('Direction')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['direction'] == 'GO'?'<div class="label label-success"> Going </div>':'<div class="label label-info"> Return </div>';
        });

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['status']?'<div class="label label-success"> Active </div>':'<div class="label label-danger"> Inactive </div>';
        });

        return $table;
    }

}