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
use App\Models\SentSMS;
use App\Models\SubRoute;
use App\Repositories\Admin\MerchantRepository;
use App\Repositories\Admin\RouteRepository;
use App\Repositories\Admin\SchedulesRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class SentSMSController extends BaseController
{

    /**
     * ScheduleController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){
        $table = $this->createSentSMSTable();
        return view('admins.pages.sms.index_sent_sms')->with(['table' => $table]);
    }

    /**
     * @return mixed
     */
    protected function createSentSMSTable()
    {
        $table = app(TableList::class)
            ->setModel(SentSMS::class)
            ->setRowsNumber(20)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.sent_sms.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('id','receiver','sender','operator','message','created_at');
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
        $table->addColumn('created_at')->setTitle('Date')->isSortable()->isSearchable()->sortByDefault();

        $table->addColumn('receiver')->setTitle('Sent to')->isSortable()->isSearchable();

        $table->addColumn('message')->setTitle('Message')->isSortable()->isSearchable();

        $table->addColumn('sender')->setTitle('Sender ID')->isSortable()->isSearchable();

        $table->addColumn('operator')->setTitle('Operator')->isSearchable();

        return $table;
    }
}