<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 9/11/2018
 * Time: 4:35 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use App\Services\Payments\Mpesa\MpesaTransactionB2C;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;

class MpesaB2CController extends Controller
{
    use MpesaTransactionB2C, MerchantPaymentProcessor;

    /**
     * @param Request $request
     * @return string
     */
    public function confirmB2CTransaction(Request $request){
        try{

            $response = $this->confirmMpesaB2CTransaction($request);

            if($response['status']){

                $mpesaB2C = $response['model'];

                $merchantPayment = $mpesaB2C->merchantPayment;

                \Log::channel('mpesab2c')->error('Merchant payment#'.json_encode($mpesaB2C) . PHP_EOL);
                $this->onMerchantPaymentSuccess($merchantPayment);
            } else{
                \Log::channel('mpesab2c')->error('Transaction settling failed'.$response['error'] . PHP_EOL);
            }

        } catch (\Exception $exception){

            if(config('app.debug_logs')){
                \Log::channel('mpesab2c')->error('Transaction settling failed'.$exception->getTraceAsString() . PHP_EOL);
            }

            \Log::channel('mpesab2c')->error('Transaction settling failed#' . $exception->getMessage() . PHP_EOL);

            return 'false';
        }
        return 'true';
    }
}