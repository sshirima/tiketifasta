<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:28 PM
 */

namespace App\Services\Payments\Mpesa;


use App\Models\MpesaB2C;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;
use Log;

trait MpesaTransactionB2C
{

    use MpesaTransactionB2CRequest;

    /**
     * @param $receipt
     * @param $amount
     * @return array|null
     */
    public function initializeMpesaB2CTransaction($receipt, $amount){
        $reply = null;
        $ch = curl_init();
        $mpesaB2C = $this->createMpesaB2CTransaction([
            'amount'=>$amount,
            'command_id'=>'BusinessPayment',
            'initiator'=>config('payments.mpesa.b2c.initiator'),
            'recipient'=>$receipt,
            'og_conversation_id'=>strtoupper(PaymentManager::random_code(16)),
            'transaction_date'=>PaymentManager::getCurrentTimestamp(),
            'transaction_id'=>strtoupper(PaymentManager::random_code(10)),
        ]);

        //Log::channel('mpesab2c')->error('Mpesa B2C transaction initiated: transactionID='.$mpesaB2C->transaction_id . PHP_EOL);

        try{
            $requestBody = $this->getMpesaB2CInitializationParameter($mpesaB2C);

            Log::channel('mpesab2c')->info('Request='.$requestBody . PHP_EOL);

            $url = config('payments.mpesa.b2c.url_initiate');

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            /*curl_setopt($ch, CURLOPT_SSLKEY, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.key');
            curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/storage/mpesa/root.pem');
            curl_setopt($ch, CURLOPT_SSLCERT, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.cer');*/
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            curl_setopt($ch, CURLOPT_TIMEOUT, config('payments.mpesa.b2c.timeout'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('payments.mpesa.b2c.connect_timeout'));

            $response = curl_exec($ch);

            if ($response === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    Log::channel('mpesab2c')->error('Connection timeout: url='.$url  . PHP_EOL);
                    $this->deleteMpesaB2CTransaction($mpesaB2C, 'Connection timeout: url='.$url);
                }
            }

            $this->setMpesaB2CTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_AUTHORIZED);

            //Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE))
                {
                    case 200:
                        //Confirm the transaction, set booking and ticket  confirmed send notification to user
                        Log::channel('mpesab2c')->info('B2C transaction initiated: transactionID='.$mpesaB2C->transaction_id . PHP_EOL);
                        $parser = new Parser();
                        $res = $parser->xml($response);

                        $res = $this->processMpesaB2CInitializationResponse($res, $mpesaB2C);

                        $reply = array('status'=>true, 'model'=>$mpesaB2C,'response'=>$res);
                        break;
                    default:
                        $this->deleteMpesaB2CTransaction($mpesaB2C, 'Unexpected HTTP code: ' . $http_code);
                        Log::channel('mpesab2c')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                        $reply = array('status'=>false, 'error'=>'Unexpected HTTP code: ' . $http_code . '[' . $response . ']' );
                }
            } else {
                Log::channel('mpesab2c')->error('Curl error[ Error code:' . curl_errno($ch) . ']' . PHP_EOL);
                $reply = array('status'=>false, 'error'=>'Curl error[ Error code:' . curl_errno($ch) . ']');
            }

        }catch(\Exception $exception){
            $reply = array('status'=>false, 'error'=>$exception->getMessage());
        }
        curl_close($ch);
        return $reply;
    }

    /**
     * @param $values
     * @return mixed
     */
    public function createMpesaB2CTransaction($values){
        return MpesaB2C::create($values);
    }

    /**
     * @param $mpesaB2C
     * @return mixed
     */
    private function getMpesaB2CInitializationParameter($mpesaB2C){
        $mpesa = new Mpesa();
        $timestamp = PaymentManager::getCurrentTimestamp();
        $spPassword = $mpesa->encryptSPPassword(config('payments.mpesa.b2c.spid'), config('payments.mpesa.b2c.sppassword'), $timestamp);

        $initiatorPassword = $mpesa->encryptInitiatorPassword(config('payments.mpesa.b2c.initiator_password'));

        return $this->b2cPaymentConfirmRequest([
            'spId' => config('payments.mpesa.b2c.spid'),
            'spPassword' => $spPassword,
            'timestamp' => $timestamp,
            'amount' => $mpesaB2C->amount,
            'commandID' => config('payments.mpesa.b2c.command_id'),
            'initiator' => config('payments.mpesa.b2c.initiator'),
            'initiatorPassword' => $initiatorPassword,
            'recipient' => $mpesaB2C->recipient,
            'originatorConversationID' => $mpesaB2C->og_conversation_id,
            'transactionDate' => $mpesaB2C->transaction_date,
            'transactionID' => $mpesaB2C->transaction_id,
        ]);
    }

    /**
     * @param $res
     * @param $mpesaB2C
     * @return mixed
     */
    protected function processMpesaB2CInitializationResponse($res, $mpesaB2C)
    {
        $mpesaB2C->result_code = $res['response']['responseCode'];
        $mpesaB2C->result_desc = isset($res['response']['responseDesc']) ? $res['response']['responseDesc'] : 'Pending_confirmation';
        $mpesaB2C->init_code = $res['response']['responseCode'];
        $mpesaB2C->init_desc = isset($res['response']['responseDesc']) ? $res['response']['responseDesc'] : 'Pending_confirmation';
        if ($res['response']['responseCode'] == '0') {
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[0];
            $this->setMpesaB2CTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_POSTED);
        } else {
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[1];
            $this->setMpesaB2CTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_FAILED);
        }

        $mpesaB2C->update();
        return $res;
    }

    /**
     * @param Request $request
     * @return array|bool
     */
    protected function confirmMpesaB2CTransaction(Request $request){
        $parser = new Parser();
        $input = $parser->xml($request->getContent());

        $resType = $input['ns3:resultType'];
        $resCode = $input['ns3:resultCode'];
        $resDesc = $input['ns3:resultDesc'];
        $orgConvId = $input['ns3:originatorConversationID'];
        $convId = $input['ns3:conversationID'];
        $trID = $input['ns3:transactionID'];
        $mpesaReceipt =$input['ns3:mpesaReceipt'];
        $resultParams =$input['ns3:resultParameters']['ns3:parameter'];

        \Log::channel('mpesab2c')->info('Confirmation request received: transactionID='.$trID .', response='.json_encode($input). PHP_EOL);

        $mpesaB2C = MpesaB2C::where(['transaction_id'=>$trID])->first();

        if(!isset($mpesaB2C)){
            Log::channel('mpesab2c')->error('Mpesa B2C transaction not found: transactionID='.$trID . PHP_EOL);
            return array('status'=>false);
        }

        if(!($mpesaB2C->og_conversation_id == $orgConvId)){
            Log::channel('mpesab2c')->error('Original conversation ID do not match: transactionID='.$trID . PHP_EOL);
            return array('status'=>false);
        }
        if ($resCode == '0'){
            $mpesaB2C->mpesa_receipt = $mpesaReceipt;
            $mpesaB2C->conversation_id = $convId;
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[2];
            $mpesaB2C->transaction_status = MpesaB2C::TRANS_STATUS_SETTLED;
            //$this->setMpesaB2cTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_SETTLED);
        } else {
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[3];
            $mpesaB2C->mpesa_receipt = $mpesaReceipt;
            Log::channel('mpesab2c')->error('Result code error: response='.json_encode($input) . PHP_EOL);
            $mpesaB2C->transaction_status = MpesaB2C::TRANS_STATUS_FAILED ;
            //$this->setMpesaB2cTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_FAILED);
        }

        $mpesaB2C->result_code = $resCode;
        $mpesaB2C->result_desc = $resDesc;
        $mpesaB2C->confirm_code = $resCode;
        $mpesaB2C->confirm_desc = $resDesc;

        $mpesaB2C->update();

        /*foreach ($resultParams as $param){
            $key = $param['ns3:key'];
            $value = $param['ns3:value'];

            if ($key == 'Amount'){

            }
        }*/
        return array('status'=>true, 'model'=>$mpesaB2C);
    }

    /**
     * @param $mpesaB2C
     * @param string $reason
     */
    protected function deleteMpesaB2CTransaction($mpesaB2C, $reason=''): void
    {
        Log::channel('mpesab2c')->error('MpesaB2C model record has been deleted#reason:'.$reason  . PHP_EOL);
        $mpesaB2C->delete();
    }

    /**
     * @param $mpesaB2C
     * @param $merchantId
     */
    public function setMerchantPaymentId($mpesaB2C, $merchantId){
        $mpesaB2C->merchant_payment_id =$merchantId;
        $mpesaB2C->update();
    }

    /**
     * @param MpesaB2C $mpesab2c
     * @param $status
     */
    public function setMpesaB2CTransactionStatus(MpesaB2C $mpesab2c, $status){
        $mpesab2c->transaction_status = $status;
        $mpesab2c->update();
    }
}