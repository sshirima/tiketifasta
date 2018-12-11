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
use App\Models\ScheduledTask;
use App\Models\SubRoute;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\SchedulesRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class ScheduleTasksController extends BaseController
{

    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request){
        $table = $this->createSchedulesTable();

        return view('admins.pages.scheduled_tasks.index')->with(['table' => $table]);
    }

    /**
     * @return mixed
     */
    protected function createSchedulesTable()
    {
        $table = app(TableList::class)
            ->setModel(ScheduledTask::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.scheduled_tasks.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('id','task_uid','task_name','task_description','interval_unit','updated_at',
                    'run_interval','last_run_status','last_run');
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
        $table->addColumn('task_uid')->setTitle('ID')->isSearchable();

        $table->addColumn('task_name')->setTitle('Name')->sortByDefault()->isSortable()->isSearchable();

        $table->addColumn('task_description')->setTitle('Description')->isSortable()->isSearchable();

        $table->addColumn('interval_unit')->setTitle('Run after every')->isSortable()->isSearchable()->isCustomHtmlElement(function ($entity, $column) {
            return $entity['run_interval'].' '.$entity['interval_unit'];
        });

        $table->addColumn('updated_at')->setTitle('Last run')->isSortable()->isSearchable()->isCustomHtmlElement(function ($entity, $column) {
            return $this->getTimeDifference($entity['last_run']);
        });

        $table->addColumn('last_run_status')->setTitle('Last run status');

        /*$table->addColumn('direction')->setTitle('Direction')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['direction'] == 'GO'?'<div class="label label-success"> Going </div>':'<div class="label label-info"> Return </div>';
        });

        $table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function ($entity, $column) {
            return $entity['status']?'<div class="label label-success"> Active </div>':'<div class="label label-danger"> Inactive </div>';
        });*/

        return $table;
    }

    public function getTimeDifference($last_run){
        $start_date = new \DateTime($last_run);
        $since_start = $start_date->diff(new \DateTime());

        if($since_start->days != 0){
            return $since_start->days. 'd'.$since_start->h.' hrs '.$since_start->i.' mins '.$since_start->s.' secs'.' ago';
        }

        if($since_start->h != 0){
            return $since_start->h.' hrs '.$since_start->i.' mins '.$since_start->s.' secs'.' ago';
        }

        if($since_start->i != 0){
            return $since_start->i.' mins '.$since_start->s.' secs'.' ago';
        }

        if($since_start->s != 0){
            return $since_start->s.' secs'.' ago';
        }

        return '';
    }

}