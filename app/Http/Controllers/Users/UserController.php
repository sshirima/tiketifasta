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

    protected $user;

    public function __construct()
    {

    }

    /**
     * @return $this
     */
    public function homepage(){
        $this->checkAuth();
        return view('users.pages.home')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function contactUs(){
        $this->checkAuth();
        return view('users.pages.contact_us')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function aboutUs(){
        $this->checkAuth();
        return view('users.pages.about_us')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function testimonials(){
        $this->checkAuth();
        return view('users.pages.testimonials')->with(['user'=>$this->user]);
    }

    /**
     *
     */
    public function checkAuth(){
        if(Auth::guard('web')->check()){
            $this->user = Auth::guard('web')->user();
        }
    }

}