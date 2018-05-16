<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Http\Controllers\Admins\BusController;
use App\Models\Bus;
use App\Models\Merchant;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class BusRepository extends BaseRepository
{
    public $conditions = array();
    public $entityColumns = array();


    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Bus::class;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){

        /*if ($request->filled(CreateBusRequest::REG_NUMBER)) {
            $this->conditions[Bus::REG_NUMBER] = $request[CreateBusRequest::REG_NUMBER];
        }*/
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    /**
     * @return mixed
     */
    public function instantiateBusTable()
    {
        $table = app(TableList::class)
            ->setModel(Bus::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => BusController::ROUTE_INDEX, 'parameters' => []],
                'create' => ['alias' =>  BusController::ROUTE_CREATE, 'parameters' => []],
                'destroy' => ['alias' =>  BusController::ROUTE_REMOVE, 'parameters' => []]
            ])->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(Merchant::TABLE, Merchant::ID, '=', Bus::MERCHANT_ID);
            });
        return $table;
    }

}