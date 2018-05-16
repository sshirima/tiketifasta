<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 10:18 PM
 */

namespace App\Repositories\Merchant;

use App\Models\Staff;
use App\Repositories\BaseRepository;

class StaffRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'firstname','lastname','email'
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    function model()
    {
        return Staff::class;
    }

}