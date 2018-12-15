<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:28 PM
 */

namespace App\Services\Payments\Mpesa;


use App\Models\MerchantPayment;
use App\Models\MpesaB2C;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;
use Log;

trait MpesaTransactionB2C
{
    use MpesaTransactionB2CRequest;

    /**
     * @param MerchantPayment $merchantPayment
     * @return mixed
     */
    protected function createMpesaB2CModel(MerchantPayment $merchantPayment)
    {
        $mpesaB2C = MpesaB2C::create($this->getMpesaB2CParametersArray($merchantPayment));
        return $mpesaB2C;
    }

    /**
     * @param $mpesaB2C
     * @return array|null
     */
    public function postTransactionMpesaB2C($mpesaB2C)
    {
        $log_action = 'Posting mpesa b2c transaction';
        $log_data = '';
        $log_format_fail = '%s, %s, %s, %s';
        $log_format_success = '%s, %s, %s';

        $reply = null;
        $ch = curl_init();
        //Log::channel('mpesab2c')->error('Mpesa B2C transaction initiated: transactionID='.$mpesaB2C->transaction_id . PHP_EOL);

        try {
            $requestBody = $this->getMpesaB2CPostingParameter($mpesaB2C);
            $log_data = 'request:'.json_encode($requestBody);

            //Log::channel('mpesab2c')->info('Request=' . $requestBody . PHP_EOL);

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

            if($response === false){
                $log_status = 'fail';
                $log_event = 'connection timed out:'.$url;
                Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,''). PHP_EOL);

                $response = $this->retryConnection($ch, $url);

                if($response === false){
                    curl_close($ch);
                    return ['status'=>false,'error'=>$log_event];
                }
            }
            /*if ($response === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    $log_status = 'fail';
                    $log_event = 'connection timed out:'.$url;
                    Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,''). PHP_EOL);
                    //$this->deleteMpesaB2CTransaction($mpesaB2C, 'Connection timeout: url='.$url);
                }
            }*/

            $this->setMpesaB2CTransactionStatus($mpesaB2C, MpesaB2C::TRANS_STATUS_POSTED);

            //Check HTTP status code
            if (!curl_errno($ch)) {
                $log_data = $log_data .',response:'.$response;
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {

                    case 200:
                        $log_status = 'success';
                        Log::info(sprintf($log_format_success,$log_action,$log_status,'reference:'.$mpesaB2C->transaction_id). PHP_EOL);
                        $parser = new Parser();
                        $res = $parser->xml($response);
                        $res = $this->processMpesaB2CInitializationResponse($res, $mpesaB2C);
                        $reply = array('status' => true, 'model' => $mpesaB2C, 'response' => $res);
                        break;
                    default:
                        $log_status = 'fail';
                        $log_event = 'unexpected HTTP code:'.$http_code;
                        Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                        //$this->deleteMpesaB2CTransaction($mpesaB2C, 'Unexpected HTTP code: ' . $http_code);
                        $reply = array('status' => false, 'error' => $log_event);
                }
            } else {
                $log_status = 'fail';
                $log_event = 'curl error:'.curl_errno($ch);
                Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                $reply = array('status' => false, 'error' => $log_event);
            }

        } catch (\Exception $ex) {
            $log_status = 'fail';
            $log_event = 'exception:'.$ex->getMessage();
            Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
            $reply = array('status' => false, 'error' => $log_event);
        }
        curl_close($ch);
        return $reply;
    }

    /**
     * @param $mpesaB2C
     * @return mixed
     */
    private function getMpesaB2CPostingParameter($mpesaB2C)
    {
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
    protected function verifyMpesaB2CCallbackRequest(Request $request)
    {
        $log_action = 'Verify mpesa b2c post response';
        $log_format_success = '%s,%s,%s,%s';
        $log_format_fail = '%s,%s,%s';

        $parser = new Parser();
        $request_json = $parser->xml($request->getContent());

        $resType = $request_json['ns3:resultType'];
        $resCode = $request_json['ns3:resultCode'];
        $resDesc = $request_json['ns3:resultDesc'];
        $orgConvId = $request_json['ns3:originatorConversationID'];
        $convId = $request_json['ns3:conversationID'];
        $trID = $request_json['ns3:transactionID'];
        $mpesaReceipt = $request_json['ns3:mpesaReceipt'];
        $resultParams = $request_json['ns3:resultParameters']['ns3:parameter'];

        //\Log::channel('mpesab2c')->info('Confirmation request received: transactionID=' . $trID . ', response=' . json_encode($input) . PHP_EOL);

        $mpesaB2C = MpesaB2C::where(['transaction_id' => $trID])->first();

        $log_data = 'request:'.json_encode($request_json);

        if (!isset($mpesaB2C)) {
            $log_event ='transaction not found:'.$trID;
            Log::error(sprintf($log_format_fail,$log_action,'fail',$log_event,$log_data). PHP_EOL);
            return array('status' => false);
        }

        if (!($mpesaB2C->og_conversation_id == $orgConvId)) {
            $log_event ='conversation id mismatch:'.$orgConvId;
            Log::error(sprintf($log_format_fail,$log_action,'fail',$log_event,$log_data). PHP_EOL);
            return array('status' => false);
        }
        if ($resCode == '0') {
            $mpesaB2C->mpesa_receipt = $mpesaReceipt;
            $mpesaB2C->conversation_id = $convId;
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[2];
            $mpesaB2C->transaction_status = MpesaB2C::TRANS_STATUS_SETTLED;

            $log_event ='result code:'.$resCode;
            Log::info(sprintf($log_format_success,$log_action,'success',$log_event,$log_data). PHP_EOL);

        } else {
            $mpesaB2C->status = MpesaB2C::STATUS_LEVEL[3];
            $mpesaB2C->mpesa_receipt = $mpesaReceipt;
            $mpesaB2C->transaction_status = MpesaB2C::TRANS_STATUS_FAILED;

            $log_event ='unexpected result code:'.$resCode;
            Log::info(sprintf($log_format_fail,$log_action,'fail',$log_event,$log_data). PHP_EOL);
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
        return array('status' => true, 'model' => $mpesaB2C);
    }

    /**
     * @param $mpesaB2C
     * @param string $reason
     */
    protected function deleteMpesaB2CTransaction($mpesaB2C, $reason = ''): void
    {
        Log::error('Deleting mpesa b2c model,' . $reason . PHP_EOL);
        $mpesaB2C->delete();
    }

    /**
     * @param MpesaB2C $mpesab2c
     * @param $status
     */
    public function setMpesaB2CTransactionStatus(MpesaB2C $mpesab2c, $status)
    {
        $mpesab2c->transaction_status = $status;
        $mpesab2c->update();
    }

    /**
     * @param MerchantPayment $merchantPayment
     * @return array
     */
    protected function getMpesaB2CParametersArray(MerchantPayment $merchantPayment): array
    {
        $account = $merchantPayment->merchantPaymentAccount;

        $receipt = $account->account_number;
        $amount = $merchantPayment->merchant_amount;

        return [
            'amount' => $amount,
            'command_id' => 'BusinessPayment',
            'initiator' => config('payments.mpesa.b2c.initiator'),
            'recipient' => $receipt,
            'merchant_payment_id' => $merchantPayment->id,
            'og_conversation_id' => strtoupper(PaymentManager::random_code(16)),
            'transaction_date' => PaymentManager::getCurrentTimestamp(),
            'transaction_id' => $merchantPayment->payment_ref,
        ];
    }
}