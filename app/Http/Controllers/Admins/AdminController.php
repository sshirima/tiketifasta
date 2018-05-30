<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 10:18 PM
 */

namespace App\Http\Controllers\Admins;


class AdminController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function homepage(){
        $this->getDefaultViewData();
        return view('admins.pages.home')->with($this->viewData);
    }

}