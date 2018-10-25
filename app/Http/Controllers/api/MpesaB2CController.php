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

class MpesaB2CController extends Controller
{
    use MpesaPaymentB2C;

    public function encryptPassword(){

    }
    public function initiateB2CTransaction(){

        $response = $this->initializeB2CPayment([
            'amount'=>'100',
            'command_id'=>'BusinessPayment',
            'initiator'=>config('payments.mpesa.b2c.url_initiate'),
            'recipient'=>'0754710618',
        ]);

        if($response['status']){
            return 'Success : '.json_encode($response['response']);
        } else {
            return 'Error: '.$response['error'];
        }
    }
}