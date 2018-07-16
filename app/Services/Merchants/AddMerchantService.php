<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/12/2018
 * Time: 10:52 PM
 */

namespace App\Services\Merchants;


use App\Models\Merchant;
use App\Models\MerchantPaymentAccount;
use App\Models\Staff;

trait AddMerchantService
{
    public function addMerchant(array $attributes){
        //Save new merchant
        $attributes['status'] = 1;
        //Encrypt staff password
        $attributes['password'] = bcrypt($attributes['password']);

        $merchant = Merchant::create($attributes);
        if (empty($merchant)){
            return false;
        }
        //Save new staff
        $attributes['merchant_id']= $merchant->id;

        $staff = Staff::create($attributes);

        if (empty($staff)){
            $merchant->delete();
            return false;
        }

        //Create payment account
        $account = MerchantPaymentAccount::create($attributes);

        if (empty($account)){
            $merchant->delete();
            return false;
        }

        return $merchant;
    }

}