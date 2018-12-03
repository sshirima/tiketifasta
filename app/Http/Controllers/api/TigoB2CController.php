<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 9/11/2018
 * Time: 4:35 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Services\Payments\Mpesa\MpesaTransactionB2C;
use App\Services\Payments\TigoUSSD\TigoTransactionB2C;

class TigoB2CController extends Controller
{
    use TigoTransactionB2C;

    public function initiateTransaction(){

        /*$response = $this->initiatePayment('255714682070', '500');

        //No comments
        if ($response['status'] == true) {
            return 'Success : ' . json_encode($response['response']);
        } else {
            return 'Error : ' . json_encode($response['error']);
        }*/
    }
}