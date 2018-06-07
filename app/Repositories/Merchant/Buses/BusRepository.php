<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant\Buses;

use App\Http\Requests\Merchant\Buses\AssignRoutesBusRequest;
use App\Models\Bus;
use App\Repositories\BaseRepository;
use App\Repositories\BusBaseRepository;
use App\Repositories\DefaultRepository;
use Illuminate\Container\Container as Application;

class BusRepository extends BaseRepository
{
    use DefaultRepository, BusBaseRepository;

    /**
     * BusRepository constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
        $this->routeIndex = 'merchant.buses.index';
        $this->routeEdit = 'merchant.buses.edit';
    }

    /**
     * @param AssignRoutesBusRequest $request
     * @param $id
     * @return mixed
     */
    public function assignRoute(AssignRoutesBusRequest $request, $id)
    {
        $bus = $this->findWithoutFail($id);

        $bus[Bus::COLUMN_ROUTE_ID] = $request->all()[Bus::COLUMN_ROUTE_ID];

        return $bus->update();
    }
}