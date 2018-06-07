<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin\Buses;

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
        $this->routeIndex = 'admin.buses.index';
        $this->routeCreate = 'admin.buses.create';
        $this->routeEdit = 'admin.buses.edit';
    }

}