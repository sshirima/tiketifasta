<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/27/2018
 * Time: 6:38 PM
 */

namespace App\Http\Controllers\Merchants;


use App\Http\Requests\Merchant\UpdateMerchantProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show(){
        return view('merchants.pages.profile.show');
    }

    public function edit(){
        return view('merchants.pages.profile.edit');
    }

    public function update(UpdateMerchantProfileRequest $request){

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
        return redirect(route('merchant.profile.show'));
    }

}