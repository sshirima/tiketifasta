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

class DashboardController extends Controller
{

    protected $user;

    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function showDashboard(){
        return view('users.pages.dashboard')->with(['user'=>\auth()->user()]);
    }

}