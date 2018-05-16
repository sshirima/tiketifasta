<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 10:18 PM
 */

namespace App\Repositories\Merchant;

use App\Http\Controllers\Merchants\BusController;
use App\Models\Bus;
use App\Models\BusRoute;
use App\Models\Location;
use App\Models\Merchant;
use App\Models\Route;
use App\Models\Staff;
use App\Models\SubRoute;
use App\Models\TicketPrice;
use App\Models\TicketPriceDefault;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TicketPriceRepository extends BaseRepository
{
    public $conditions = array();
    public $entityColumns = array();


    /**
     * @var array
     */
    protected $fieldSearchable = [

    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return TicketPrice::class;
    }

    /**
     * @param array $columns
     */
    public function setReturnColumn(array $columns){
        $this->entityColumns = $columns;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request){

        if ($request->filled(Bus::ID)) {
            $this->conditions[Bus::ID] = $request[Bus::ID];
        }
    }

    /**
     * @return mixed
     */
    public function instantiateTicketPriceTable()
    {
        $table = app(TableList::class)
            ->setModel(Bus::class)
            ->setRoutes([
                'index' => ['alias' => BusController::ROUTE_INDEX, 'parameters' => []]])
            ->setRowsNumber(10)
            ->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(BusRoute::TABLE,  BusRoute::BUS_ID, '=',Bus::ID)
                    ->join(SubRoute::TABLE, SubRoute::BUS_ROUTE_ID, '=', BusRoute::ID)
                    ->leftJoin(TicketPrice::TABLE, SubRoute::ID, '=', TicketPrice::SUB_ROUTE_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', SubRoute::SOURCE)
                    ->join(Location::TABLE.' as B', 'B.id', '=', SubRoute::DESTINATION)
                    ->where($this->conditions);
            });
        return $table;
    }

    /**
     * @param $subRoute
     */
    public static function createDefaultPrice($subRoute): void
    {
        $ticketPriceDefault = TicketPriceDefault::select(['price'])
            ->where(['start_location' => $subRoute->source, 'last_location' => $subRoute->destination])
            ->where(['last_location' => $subRoute->source, 'start_location' => $subRoute->destination])
            ->first();

        if (!empty($ticketPriceDefault)) {
            TicketPrice::updateOrCreate([
                TicketPrice::COLUMN_SUB_ROUTE_ID => $subRoute->id,
                TicketPrice::COLUMN_TICKET_TYPE => 'Adult',
            ], [
                TicketPrice::COLUMN_PRICE => $ticketPriceDefault[TicketPrice::COLUMN_PRICE],
            ]);
        }
    }
}