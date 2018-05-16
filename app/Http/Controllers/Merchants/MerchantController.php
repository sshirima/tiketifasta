<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 5:54 PM
 */

namespace App\Http\Controllers\Merchants;


class MerchantController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:merchant');
    }

    public function homepage(){
        return view('merchants.pages.home')->with(['merchant'=>\auth()->user()]);
    }

}