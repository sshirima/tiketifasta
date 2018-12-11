<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/10/2018
 * Time: 8:36 PM
 */

namespace App\Services\Payments\MerchantPayments;


use App\Models\MerchantPayment;
use Illuminate\Support\Facades\Log;

trait PayMerchant
{
    /**
     * @param MerchantPayment $merchantPayment
     * @return array
     */
    public function issueMpesaPayments(MerchantPayment $merchantPayment){

        try{
            $account = $merchantPayment->merchantPaymentAccount;

            $response = $this->initializeMpesaB2CTransaction($account->account_number, $merchantPayment->merchant_amount);

            if ($response['status']){

                $this->setMerchantPaymentId($response['model'], $merchantPayment->id);

                $this->onMerchantPaymentInitiated($merchantPayment);

                return ['status'=>true];
            }else {
                $this->onMerchantPaymentFailure($merchantPayment);
                return ['status'=>false,'error'=>'Merchant payment failure'];
            }

        }catch(\Exception $ex){
            if(config('app.debug_logs')){
                Log::error('Fail to process merchant payment on Mpesa#error='.$ex->getTraceAsString());
            }
            return ['status'=>false,'error'=>'Fail to process merchant payment on Mpesa#error='.$ex->getMessage()];
        }
    }

    /**
     * @param MerchantPayment $merchantPayment
     * @return array
     */
    public function issueTigoPesaPayment(MerchantPayment $merchantPayment){
        try{
            $account = $merchantPayment->merchantPaymentAccount;

            $response = $this->initializeTigoB2CTransaction($account->account_number, $merchantPayment->merchant_amount);

            if ($response['status']){

                $this->setMerchantPaymentId($response['model'],$merchantPayment->id );

                $this->onMerchantPaymentSuccess($merchantPayment);

                return ['status'=>true];
            }else {
                $this->onMerchantPaymentFailure($merchantPayment);
                return ['status'=>false,'error'=>'Merchant payment failure'];
            }
        }catch(\Exception $ex){
            if(config('app.debug_logs')){
                Log::error('Fail to process merchant payment on Tigopesa#error='.$ex->getTraceAsString());
            }
            return ['status'=>false,'error'=>'Fail to process merchant payment on Tigopesa#error='.$ex->getMessage()];
        }
    }
}