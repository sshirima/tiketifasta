<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 5:54 PM
 */

namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    public function homepage(){
        return view('users.pages.home');
    }

    public function contactUs(){
        return view('users.pages.contact_us');
    }

    public function aboutUs(){
        return view('users.pages.about_us');
    }
    public function testimonials(){
        return view('users.pages.testimonials');
    }

}