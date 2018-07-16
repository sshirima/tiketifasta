<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant\Buses;

use App\Models\Location;
use App\Models\Trip;
use App\Repositories\BaseRepository;
use App\Repositories\DefaultRepository;
use App\Services\BusesRoutes\TripManager;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Okipa\LaravelBootstrapTableList\TableList;

class TripRepository extends BaseRepository
{
    use DefaultRepository, TripManager;

    /**
     * BusRepository constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->routeIndex = 'merchant.buses.index';
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Trip::class;
    }


    public function createOrUpdate(array $trips, $busId)
    {
        foreach ($trips as $trip) {
            $trip[Trip::COLUMN_BUS_ID] = $busId;
            $this->updateOrCreate([
                Trip::COLUMN_SOURCE => $trip[Trip::COLUMN_SOURCE],
                Trip::COLUMN_DESTINATION => $trip[Trip::COLUMN_DESTINATION],
                Trip::COLUMN_BUS_ID => $busId,
            ], [
                Trip::COLUMN_ARRIVAL_TIME => $trip[Trip::COLUMN_ARRIVAL_TIME],
                Trip::COLUMN_DEPART_TIME => $trip[Trip::COLUMN_DEPART_TIME],
                Trip::COLUMN_TRAVELLING_DAYS => $trip[Trip::COLUMN_TRAVELLING_DAYS],
                Trip::COLUMN_STATUS => $trip[Trip::COLUMN_STATUS],
                Trip::COLUMN_DIRECTION => $trip[Trip::COLUMN_DIRECTION],
            ]);
        }
        return true;
    }

    /**
     * @param Request $request
     * @param $tripId
     * @return bool
     */
    public function updateTripTime(Request $request, $tripId){

        $trip = $this->findWithoutFail($tripId);

        if (!empty($trip)){
            $trip->arrival_time = $this->convertTime($request->all()['arrival_time']);
            $trip->depart_time = $this->convertTime($request->all()['depart_time']);
            $trip->update();
            return $trip;
        } else {
            return false;
        }
    }

    /**
     * @param $table
     * @return mixed
     */
    public function setCustomTable($table)
    {

        $table->addQueryInstructions(function ($query) {
            $query->select($this->entityColumns)
                ->join(Location::TABLE . ' as A', 'A.id', '=', Trip::COLUMN_SOURCE)
                ->join(Location::TABLE . ' as B', 'B.id', '=', Trip::COLUMN_DESTINATION)
                ->where($this->conditions);
        });

        return $table;
    }

    /**
     * @param Request $request
     */
    public function setConditions(Request $request)
    {
        $this->conditions = array(Trip::BUS_ID=>$request[Trip::COLUMN_BUS_ID]);
    }

    /**
     * @return mixed
     */
    public function instantiateTableList()
    {
        $table = app(TableList::class)
            ->setModel($this->model())
            ->setRowsNumber(10)
            ->setRoutes($this->getTableRoutes());
        return $table;
    }
}