<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 1/13/2018
 * Time: 6:51 PM
 */

namespace App\Http\Controllers\Merchants\Auth;

use App\Http\Controllers\Admins\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laracasts\Flash\Flash;

class ChangePasswordController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:merchant');
    }

    public function showForm(){
        return view('merchants.pages.auth.changepass')->with($this->defaultParameters());
    }

    public function changePassword(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches with the password you provided. Please try again.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);

        //Change Password
        $merchant = Auth::user();
        $merchant->password = bcrypt($request->get('new-password'));
        $merchant->save();

        Flash::success('User password changes successfully!');

        return view('merchants.pages.profile.show')->with($this->defaultParameters());
        //return redirect()->back()->with("success","Password changed successfully !");
    }

}