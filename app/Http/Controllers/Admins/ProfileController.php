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
    private $adminRepo;

    public function __construct(AdminRepository $repository)
    {
        $this->middleware('auth:admin');
        $this->adminRepo = $repository;
    }

    public function show(){
        return view('admins.pages.profile.show')->with($this->defaultParameters());
    }


    public function edit(){
        return view('admins.pages.profile.edit')->with($this->defaultParameters());
    }

    public function update($id, UpdateProfileRequest $request){
        $admin = $this->adminRepo->findWithoutFail($id);

        if (empty($admin)){
            Flash::error(__('page_profile_show.account_not_found'));
        }

        $admin = $this->adminRepo->update($request->all(), $id);

        Flash::success(__('page_profile_show.account_updated'));

        Auth::setUser($admin);

        return view('admins.pages.profile.show')->with($this->defaultParameters());
    }

}