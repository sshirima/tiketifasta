<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 9/11/2018
 * Time: 4:35 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Services\Payments\Mpesa\MpesaPaymentB2C;
use App\Services\Payments\TigoUSSD\TigoUssdB2C;

class TigoB2CController extends Controller
{
    public function initiateTransaction(){

        $tigoB2C = new TigoUssdB2C();

        $response = $tigoB2C->initiatePayment('255655791244', '1000');

        //No comments
        if ($response['status'] == true) {
            return 'Success : ' . json_encode($response['response']);
        } else {
            return 'Error : ' . json_encode($response['error']);
        }
    }
}