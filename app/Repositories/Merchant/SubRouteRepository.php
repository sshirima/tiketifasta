<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/13/2018
 * Time: 7:15 PM
 */

namespace App\Repositories\Merchant;


use App\Http\Controllers\Admins\SubRouteController;
use App\Http\Controllers\Merchants\BusController;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Day;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class SubRouteRepository extends BaseRepository
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
        return SubRoute::class;
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    public function getBusSubRoutes(Request $request, $id){

        $this->setConditions($request);

        $table = $this->instantiateSubRouteTable();

        return $table;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request): void
    {
        /*if ($request->filled(BusRoute::COLUMN_BUS_ID)) {
            $this->conditions[Bus::ID] = $request[BusRoute::COLUMN_BUS_ID];
        }*/
    }
    /**
     * @return mixed
     */
    public function instantiateSubRouteTable()
    {
        $table = app(TableList::class)
            ->setModel(SubRoute::class)
            ->setRoutes([
                'index' => ['alias' => SubRouteController::ROUTE_INDEX, 'parameters' => []]])
            ->setRowsNumber(10)
            ->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(BusRoute::TABLE,  BusRoute::ID, '=',SubRoute::BUS_ROUTE_ID)
                    ->join(TicketPrice::TABLE,  TicketPrice::SUB_ROUTE_ID, '=',SubRoute::ID)
                    ->join(Bus::TABLE,  BusRoute::BUS_ID, '=',Bus::ID)
                    ->join(Merchant::TABLE,  Merchant::ID, '=',Bus::MERCHANT_ID)
                    ->join(Route::TABLE, Route::ID, '=', BusRoute::ROUTE_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });
        return $table;
    }

}