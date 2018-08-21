<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:27 PM
 */

namespace App\Services\Payments\Mpesa;


class Mpesa
{

    use MpesaPaymentC2B;

    protected function encryptServiceProviderPassword($spId, $password, $timestamp){
        //$timestamp = date('YmdHis');
        return strtoupper(base64_encode(hash('sha256',$spId.$password.$timestamp)));
    }

    protected function encryptPassword($spId, $spPassword, $timestamp)
    {
        return base64_encode(hash("sha256", $spId.$spPassword.$timestamp, true));
    }

    public function getPaymentReference(){
        return mt_rand();
    }
}