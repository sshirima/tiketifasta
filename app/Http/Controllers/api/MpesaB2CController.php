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

                $merchantPayment = $mpesaB2C->merchantPayment()->first();

                $this->onMerchantPaymentSuccess($merchantPayment);
            } else{
                \Log::channel('mpesab2c')->error('Transaction settling failed: error='.$response['error'] . PHP_EOL);
            }

        } catch (\Exception $exception){
            if(config('app.debug_logs')){
                \Log::channel('mpesab2c')->error('Transaction settling failed: error='.$exception->getTraceAsString() . PHP_EOL);
            } else {
                \Log::channel('mpesab2c')->error('Transaction settling failed: error=' . $exception->getMessage() . PHP_EOL);
            }

            return 'Error:'.$exception->getTraceAsString();
        }
    }
}