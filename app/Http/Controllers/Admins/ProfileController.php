<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 2/17/2018
 * Time: 7:57 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Repositories\Admin\AdminRepository;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class ProfileController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function show(){
        return view('admins.pages.profile.show');
    }


    public function edit(){
        return view('admins.pages.profile.edit');
    }

    public function update(UpdateProfileRequest $request){
        $input = $request->all();

        $staff = auth()->user();

        if($request->has('firstname')){
            $staff->firstname = $input['firstname'];
        }

        if($request->has('lastname')){
            $staff->lastname = $input['lastname'];
        }

        if($request->has('phonenumber')){
            $staff->phonenumber = $input['phonenumber'];
        }

        $staff->update();

        Auth::user()->fresh();

        //return $request->all();
        return redirect(route('admin.profile.show'));
    }

}