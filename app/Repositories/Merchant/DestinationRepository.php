<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Merchant;

use App\Models\SubRoute;
use App\Models\Location;
use App\Repositories\BaseRepository;

class DestinationRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return SubRoute::class;
    }
}