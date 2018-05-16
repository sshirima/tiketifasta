<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/12/2018
 * Time: 10:17 PM
 */

namespace App\Admin;


use App\Repositories\Merchant\MerchantRepository;
use App\Repositories\Merchant\StaffRepository;

class MerchantAccountManager
{
    private $merchantRepository;
    private $staffRepository;

    public function __construct(MerchantRepository $merchantRepo, StaffRepository $staffRepo)
    {
        $this->merchantRepository = $merchantRepo;
        $this->staffRepository = $staffRepo;
    }

    public function createNewAccount($input){
        //Save new merchant
        $merchant = $this->merchantRepository->create($input);
        if (empty($merchant)){
            return false;
        }
        //Save new staff
        $input['merchant_id']= $merchant->id;
        //Encrypt staff password
        $input['password'] = bcrypt($input['password']);

        $staff = $this->staffRepository->create($input);

        if (empty($staff)){
            $this->merchantRepository->delete($merchant->id);
            return false;
        }

        return $merchant;
    }
}