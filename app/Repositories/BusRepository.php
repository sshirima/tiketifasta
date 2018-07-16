<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/7/2018
 * Time: 3:00 PM
 */

namespace App\Repositories;


use App\Http\Requests\Merchant\Buses\AssignRoutesBusRequest;
use App\Models\Bus;

class BusRepository extends BaseRepository
{

    use DefaultRepository, BusBaseRepository;
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