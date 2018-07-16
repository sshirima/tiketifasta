<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 1:56 PM
 */

namespace App\Services\Merchants;


use App\Models\Merchant;

trait CheckMerchantContractStatus
{

    public function getMerchantContractStatus(Merchant $merchant){
        $now = new \DateTime('now');
        $contract_end = new \DateTime($merchant->contract_end);
        $diff = date_diff(date_create ($merchant->contract_end), date_create ());
        $merchant->contract_status = $contract_end > $now;
        $merchant->contract_days = $diff->format ('%a');;

        return $merchant;
    }
}