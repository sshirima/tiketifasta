<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:27 PM
 */

namespace App\Services\Payments\Mpesa;

use Log;

class Mpesa
{

    use MpesaPaymentC2B;

    /*protected function encryptServiceProviderPassword($spId, $password, $timestamp){
        //$timestamp = date('YmdHis');
        return strtoupper(base64_encode(hash('sha256',$spId.$password.$timestamp)));
    }*/

    public function encryptSPPassword($spId, $spPassword, $timestamp)
    {
        return base64_encode(strtoupper(hash('sha256', $spId.$spPassword.$timestamp)));
        //return base64_encode(hash("sha256", $spId.$spPassword.$timestamp, true));
    }

    public function encryptInitiatorPassword($password)
    {
        $exist =  \Storage::disk('mpesa')->exists('prod-apicrypt.cer');
        if ($exist){
            $pk =  openssl_pkey_get_public(\Storage::disk('mpesa')->get('prod-apicrypt.cer'));
            openssl_public_encrypt($password, $encrypted, $pk);
            return base64_encode($encrypted);
        } else {
            Log::channel('mpesab2c')->error('Certificate file for b2c encryption not found'. PHP_EOL );
            return $password;
        }
    }

    public function getPaymentReference(){
        return mt_rand();
    }
}