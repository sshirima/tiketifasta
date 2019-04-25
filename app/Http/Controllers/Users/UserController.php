<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 5:54 PM
 */

namespace App\Http\Controllers\Users;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

    protected $user;

    public function __construct()
    {

    }

    /**
     * @param Request $request
     * @return $this
     */
    public function homepage(Request $request){
        $this->checkAuth();
        $this->checkLanguage($request);
        return view('users.pages.home')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function contactUs(Request $request){
        $this->checkAuth();
        $this->checkLanguage($request);
        return view('users.pages.contact_us')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function aboutUs(Request $request){
        $this->checkAuth();
        $this->checkLanguage($request);
        return view('users.pages.about_us')->with(['user'=>$this->user]);
    }

    /**
     * @return $this
     */
    public function testimonials(Request $request){
        $this->checkAuth();
        $this->checkLanguage($request);
        return view('users.pages.testimonials')->with(['user'=>$this->user]);
    }

    /**
     * @param Request $request
     */
    private function checkLanguage(Request $request){
        if($request->has('locale')){
            $locale = $request->get('locale');
            App::setLocale($locale);
        }
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