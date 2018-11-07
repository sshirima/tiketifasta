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
use App\Services\Payments\PaymentManager;

class MpesaB2CController extends Controller
{
    use MpesaPaymentB2C;

    public function encryptPassword(){

    }
    public function initiateTransaction(){

        $response = $this->initiatePayment([
            'amount'=>'100',
            'command_id'=>'BusinessPayment',
            'initiator'=>config('payments.mpesa.b2c.initiator'),
            'recipient'=>'0754710618',
            'recipient'=>PaymentManager::random_code(12),
            'transaction_date'=>PaymentManager::getCurrentTimestamp(),
            'transaction_id'=>strtoupper(PaymentManager::random_code(10)),
        ]);

        if($response['status']){
            return 'Success : '.json_encode($response['response']);
        } else {
            return 'Error: '.$response['error'];
        }
    }
}