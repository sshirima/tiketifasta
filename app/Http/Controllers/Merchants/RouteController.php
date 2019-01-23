<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/15/2018
 * Time: 9:05 AM
 */

namespace App\Http\Controllers\Merchants;

use App\Http\Requests\Admin\CreateRouteRequest;
use App\Models\Route;
use App\Repositories\Admin\LocationRepository;
use App\Repositories\Admin\RouteRepository;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Okipa\LaravelBootstrapTableList\TableList;

class RouteController extends BaseController
{
    /**
     * RouteController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request){

        $table = $this->createRoutesTable();

        return view('merchants.pages.routes.index')->with(['table'=>$table]);
    }

    /**
     * @return mixed
     */
    protected function createRoutesTable()
    {
        $table = app(TableList::class)
            ->setModel(Route::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.routes.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('routes.route_name','A.name as from','B.name as to')
                    ->join('locations as A','routes.start_location','=','A.id')
                    ->join('locations as B','routes.end_location','=','B.id');
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
        $table->addColumn('route_name')->setTitle('Route name')->isSearchable()->isSortable();

        $table->addColumn('name')->setTitle('Start location')->isSearchable()->isSortable()->setCustomTable('locations')->isCustomHtmlElement(function($entity, $column){
            return $entity['from'];
        });

        $table->addColumn('name')->setTitle('Destination location')->isSearchable()->isSortable()->setCustomTable('locations')->isCustomHtmlElement(function($entity, $column){
            return $entity['to'];
        });

        return $table;
    }

}