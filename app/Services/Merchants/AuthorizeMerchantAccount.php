<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Merchants;


use App\Models\Merchant;

trait AuthorizeMerchantAccount
{

    /**
     * @param Merchant $merchant
     */
    public function enableMerchantAccount(Merchant $merchant){
        $merchant->status = 1;
        $merchant->update();

    }

    /**
     * @param Merchant $merchant
     */
    public function disableMerchantAccount(Merchant $merchant){
        $merchant->status = 0;
        $merchant->update();
    }
}