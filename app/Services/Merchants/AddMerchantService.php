<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/12/2018
 * Time: 10:52 PM
 */

namespace App\Services\Merchants;


use App\Jobs\RegisterMerchantJob;
use App\Mail\RegisterMerchantMail;
use App\Models\Merchant;
use App\Models\MerchantPaymentAccount;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

trait AddMerchantService
{
    public function addMerchant(array $attributes){
        //Save new merchant
        $attributes['status'] = 1;

        $merchant = Merchant::create($attributes);
        if (empty($merchant)){
            return false;
        }
        //Save new staff
        $attributes['merchant_id']= $merchant->id;

        $attributes = $this->generateDefaultValues($attributes);

        $defaultCreated = $this->createDefaultAccount($merchant, $attributes);

        if ($defaultCreated){
            //Create payment account
            $account = MerchantPaymentAccount::create($attributes);

            if (empty($account)){
                $merchant->delete();
                return false;
            }

        } else {
            return false;
        }

        return $merchant;
    }

    public function generateDefaultValues($attributes){
        //Create Default first name
        $attributes['firstname'] = 'Root';
        //Create Default last name
        $attributes['lastname'] = 'Account';
        //Create default random password
        $attributes['plain_password'] = str_random(8);
        return $attributes;

    }

    public function createDefaultAccount(Merchant $merchant, $attributes){
        //Encrypt staff password
        $attributes['password'] = bcrypt($attributes['plain_password']);

        $account = Staff::create($attributes);

        if (empty($account)){
            $merchant->delete();
            return false;
        }
        //Send notification email
        RegisterMerchantJob::dispatch($merchant, $account, $attributes['plain_password']);
        return true;
    }

}