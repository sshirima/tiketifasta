<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/10/2018
 * Time: 8:36 PM
 */

namespace App\Services\Payments\MerchantPayments;


use App\Models\MerchantPayment;

trait PayMerchant
{
    /**
     * @param MerchantPayment $merchantPayment
     * @return array
     */
    public function issueMpesaPayments(MerchantPayment $merchantPayment){

        try{
            $account = $merchantPayment->merchantPaymentAccount;

            $response = $this->initializeMpesaB2CTransaction($account->account_number, $this->merchantPayment->merchant_amount);

            if ($response['status']){

                $this->setMerchantPaymentId($response['model'], $this->merchantPayment->id);

                $this->onMerchantPaymentInitiated($this->merchantPayment);

                return ['status'=>true];
            }else {
                $this->onMerchantPaymentFailure($this->merchantPayment);
                return ['status'=>false,'error'=>'Merchant payment failure'];
            }

        }catch(\Exception $ex){
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

            $response = $this->initializeTigoB2CTransaction($account->account_number, $this->merchantPayment->merchant_amount);

            if ($response['status']){

                $this->setMerchantPaymentId($response['model'],$this->merchantPayment->id );

                $this->onMerchantPaymentSuccess($this->merchantPayment);

                return ['status'=>true];
            }else {
                $this->onMerchantPaymentFailure($this->merchantPayment);
                return ['status'=>false,'error'=>'Merchant payment failure'];
            }
        }catch(\Exception $ex){
            return ['status'=>false,'error'=>'Fail to process merchant payment on Mpesa#error='.$ex->getMessage()];
        }
    }
}