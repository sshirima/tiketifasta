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
use Illuminate\Support\Facades\Log;
use Nathanmac\Utilities\Parser\Parser;

class MpesaB2CController extends Controller
{
    use MerchantPaymentProcessor;

    /**
     * @param Request $request
     * @return string
     */
    public function confirmB2CTransaction(Request $request){
        $log_action = 'Receiving mpesa b2c post confirmation';
        $log_data = '';
        $log_format_success = '%s, %s, %s';
        $log_format_fail = '%s, %s, %s, %s';

        try{
            $log_data = 'request:'.$request->getContent();
            Log::info(sprintf($log_format_success,$log_action,'success',$log_data). PHP_EOL);

            $response = $this->verifyMpesaB2CCallbackRequest($request);

            if($response['status']){

                $mpesaB2C = $response['model'];

                $merchantPayment = $mpesaB2C->merchantPayment;

                $this->onMerchantPaymentSuccess($merchantPayment);
            }

        } catch (\Exception $ex){
            $log_event = 'exception:'.$ex->getMessage();
            Log::error(sprintf($log_format_fail,$log_action,'fail',$log_event,$log_data). PHP_EOL);
            return 'false';
        }
        return 'true';
    }
}