<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 2/17/2018
 * Time: 7:57 PM
 */

namespace App\Http\Controllers\User;


use App\Http\Controllers\Admins\BaseController;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;
use Laracasts\Flash\Flash;

class ProfileController extends BaseController
{
    private $userRepo;

    public function __construct(UserRepository $repository)
    {
        $this->middleware('auth');
        $this->userRepo = $repository;
    }

    public function show(){
        return view('users.pages.profile.show')->with($this->defaultParameters());
    }


    public function edit(){
        return view('users.pages.profile.edit')->with($this->defaultParameters());
    }

    public function update($id, UpdateProfileRequest $request){
        $user = $this->userRepo->findWithoutFail($id);

        if (empty($user)){
            Flash::error(__('page_profile_show.account_not_found'));
        }

        $user = $this->userRepo->update($request->all(), $id);

        Flash::success(__('page_profile_show.account_updated'));

        Auth::setUser($user);

        return view('users.pages.profile.show')->with($this->defaultParameters());
    }

}