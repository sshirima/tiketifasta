<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 10:18 PM
 */

namespace App\Repositories\Admin;

use App\Http\Controllers\Admins\TicketPriceController;
use App\Models\Bus;
use App\Models\BusClass;
use App\Models\Location;
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
        return TicketPriceDefault::class;
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
            ->setModel(TicketPriceDefault::class)
            ->setRoutes([
                'index' => ['alias' => TicketPriceController::ROUTE_INDEX, 'parameters' => []],
                'create' => ['alias' => TicketPriceController::ROUTE_CREATE, 'parameters' => []],
                'edit' => ['alias' => TicketPriceController::ROUTE_EDIT, 'parameters' => []],
            ])
            ->setRowsNumber(10)
            ->addQueryInstructions(function ($query) {
                $query->select($this->entityColumns)
                    ->join(BusClass::TABLE,  BusClass::ID, '=',TicketPriceDefault::BUS_CLASS_ID)
                    ->join(Location::TABLE.' as A', 'A.id', '=', TicketPriceDefault::START_LOCATION)
                    ->join(Location::TABLE.' as B', 'B.id', '=', TicketPriceDefault::LAST_LOCATION)
                    ->where($this->conditions);
            });
        return $table;
    }
}