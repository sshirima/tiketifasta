<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 10:18 PM
 */

namespace App\Http\Controllers\Admins;

use App\Domain\Admin\Dashboard\DashboardAggregate;

class AdminController extends BaseController
{

    private  $dashboard;
    public function __construct(DashboardAggregate $dashboard)
    {
        parent::__construct();
        $this->dashboard = $dashboard;
    }

    public function homepage(){

        $data = $this->dashboard->getDashBoardData();

        return view('admins.pages.home', compact('data'));
    }

}