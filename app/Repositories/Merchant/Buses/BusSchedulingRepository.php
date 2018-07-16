<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant\Buses;

use App\Models\Day;
use App\Models\Schedule;
use App\Repositories\BaseRepository;
use App\Repositories\DefaultRepository;
use App\Services\BusesRoutes\TripManager;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;

class BusSchedulingRepository extends BaseRepository
{
    use DefaultRepository, TripManager;

    /**
     * BusRepository constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Schedule::class;
    }

    public function createSchedules(Request $request, $busId){

        if($request->has('direction')){
            $direction = $request->has('direction');
        } else {
            $direction = 'GO';
        }


        foreach ($request->all()[ 'trip_dates'] as $key=>$date){

            $day = Day::firstOrCreate(['date' => $date]);

            $this->updateOrCreate([
                Schedule::COLUMN_BUS_ID=>$busId,
                Schedule::COLUMN_DAY_ID=>$day->id,
            ],[
                Schedule::COLUMN_STATUS => 1,
                Schedule::COLUMN_DIRECTION => $direction,
            ]);
        }

        return true;
    }
}