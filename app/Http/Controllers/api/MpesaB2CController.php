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
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;

class MpesaB2CController extends Controller
{
    use MpesaPaymentB2C;

    public function encryptPassword(){

    }
    public function initiateB2CTransaction(){

        $response = $this->initializeMpesaB2CTransaction('0754710618','100');

        if($response['status']){
            return 'Success : '.json_encode($response['response']);
        } else {
            return 'Error: '.$response['error'];
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function confirmB2CTransaction(Request $request){
        try{
            /*$parser = new Parser();
            $input = $parser->xml($request->getContent());*/
            $response = $this->confirmMpesaB2CTransaction($request);
        } catch (\Exception $exception){
            return 'Error:'.$exception->getMessage();
        }
        return $response?json_encode('Success'):'Error';
    }
}