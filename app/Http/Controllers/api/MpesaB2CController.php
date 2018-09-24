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

        try{
            $mpesaB2C = $this->createB2CTransaction([
                'amount'=>'100',
                'command_id'=>'BusinessPayment',
                'initiator'=>env('MPESA_B2C_INITIATOR'),
                'recipient'=>'255754710618',
            ]);

            $response = $this->initializeB2CPayment($mpesaB2C);

            //No comments
            if(isset($response)){
                return json_encode($response);
            } else {
                return 'Something went wrong';
            }
        }catch (\Exception $ex){
            return $ex->getMessage();
        }
    }
}