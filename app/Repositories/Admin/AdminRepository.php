<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 7:21 PM
 */

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Repositories\BaseRepository;

class AdminRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'firstname', 'lastname', 'email'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Admin::class;
    }
}