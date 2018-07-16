<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin\Buses;

use App\Models\Location;
use App\Models\Trip;
use App\Repositories\BaseRepository;
use App\Repositories\DefaultRepository;
use App\Services\BusesRoutes\TripManager;
use Illuminate\Container\Container as Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Okipa\LaravelBootstrapTableList\TableList;

class BusTripRepository extends BaseRepository
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
        return Trip::class;
    }
}