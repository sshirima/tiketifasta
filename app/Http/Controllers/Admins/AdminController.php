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
        $this->middleware('auth:admin');
    }

    public function homepage(){
        return view('admins.pages.home')->with($this->defaultParameters());
    }

}