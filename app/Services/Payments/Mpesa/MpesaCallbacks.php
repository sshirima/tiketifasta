<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:28 PM
 */

namespace App\Services\Payments\Mpesa;


use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;

trait MpesaCallbacks
{
    use MpesaPaymentC2B;
    public function c2bPaymentInitiatedRequest(Request $request){
        //Save the request details
        $parser = new Parser();
        $paymentInfo = $parser->xml($request->getContent());

        //Dispatch Payment processing Job

        //Respond to broker with unique serviceID

    }

    public function b2CPaymentConfirmationRequest(Request $request){
        //Save the confirmation details

        //Respond to broker
    }
}