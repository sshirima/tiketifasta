<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/10/2018
 * Time: 8:36 PM
 */

namespace App\Services\Payments\MerchantPayments;


use App\Models\MerchantPayment;
use App\Services\Payments\Mpesa\MpesaTransactionB2C;
use App\Services\Payments\Tigosecure\TigoTransactionB2C;
use Illuminate\Support\Facades\Log;

trait PayMerchant
{
    use MpesaTransactionB2C, TigoTransactionB2C;
    /**
     * @param MerchantPayment $merchantPayment
     * @return array
     */
    public function issueMpesaPayments(MerchantPayment $merchantPayment){

        try{

            $mpesaB2c = $merchantPayment->mpesaB2C;

            if(!isset($mpesaB2c)){

                $mpesaB2c = $this->createMpesaB2CModel($merchantPayment);

                $response = $this->initializeMpesaB2CTransaction($mpesaB2c);

                if ($response['status']){

                    $this->setMerchantPaymentId($response['model'], $merchantPayment->id);

                    $this->onMerchantPaymentInitiated($merchantPayment);

                    return ['status'=>true];
                }else {
                    $mpesaB2c->delete();

                    Log::channel('mpesab2c')->error('MpesaB2C model record has been deleted#error='.$response['error'] . PHP_EOL);

                    $this->onMerchantPaymentFailure($merchantPayment);

                    return ['status'=>false,'error'=>'Merchant payment failure'];
                }
            }
            return ['status'=>false,'error'=>'Merchant payment on progress'];

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
            $tigoB2C = $merchantPayment->tigoB2C;

            if(!isset($tigoB2C)){

                $tigoB2C = $this->createTigoB2CModel($merchantPayment);

                $response = $this->initializeTigoB2CTransaction($tigoB2C);

                if ($response['status']){

                    $this->setMerchantPaymentId($response['model'],$merchantPayment->id );

                    $this->onMerchantPaymentSuccess($merchantPayment);

                    return ['status'=>true];
                } else {
                    $tigoB2C->delete();

                    Log::channel('mpesab2c')->error('TigoB2C model record has been deleted#error='.json_encode($response['error']) . PHP_EOL);

                    $this->onMerchantPaymentFailure($merchantPayment);

                    return ['status'=>false,'error'=>'Merchant payment failure'];
                }
            }
            return ['status'=>false,'error'=>'Merchant payment on progress'];

        }catch(\Exception $ex){
            if(config('app.debug_logs')){
                Log::error('Fail to process merchant payment on Tigopesa#error='.$ex->getTraceAsString());
            }
            return ['status'=>false,'error'=>'Fail to process merchant payment on Tigopesa#error='.$ex->getMessage()];
        }
    }
}