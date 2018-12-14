<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/23/2018
 * Time: 1:50 PM
 */

namespace App\Services\Payments\Tigosecure;

use App\Models\TigoB2C;
use Log;
use Nathanmac\Utilities\Parser\Parser;

trait TigoTransactionB2C
{
    use TigoTransactionB2CRequest;

    /**
     * @param $tigoB2C
     * @return array|null
     */
    public function postTigoB2CTransaction($tigoB2C)
    {
        $log_action = 'Posting tigo b2c transaction';
        $log_data = '';
        $log_format_fail = '%s,%s,%s,%s';
        $log_format_success = '%s,%s,%s';

        $reply = null;
        $ch = curl_init();
        try {
            //Create TigoB2C
            $requestContent = $this->getTigoB2cPostingParamsXml($this->getTigoB2cPostingParamsArray($tigoB2C));
            $log_data = 'request:'.json_encode($requestContent);
            $url = config('payments.tigo.bc2.url');

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestContent);

            curl_setopt($ch, CURLOPT_TIMEOUT, config('payments.tigo.bc2.timeout'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('payments.tigo.bc2.connect_timeout'));

            $response = curl_exec($ch);

            if ($response === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    $log_status = 'fail';
                    $log_event = 'connection timed out:'.$url;
                    Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,''). PHP_EOL);
                    $this->deleteTigoB2CTransactionModel($tigoB2C, $log_event);
                }
            }

            //Check HTTP status code
            if (!curl_errno($ch)) {
                $log_data = $log_data .',response:'.$response;
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        $parser = new Parser();
                        $reply = $this->verifyTigoB2CResponse($tigoB2C, $parser->xml($response), $log_data);
                        break;
                    default:
                        $log_status = 'fail';
                        $log_event = 'unexpected HTTP code:'.$http_code;
                        Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                        $this->deleteTigoB2CTransactionModel($tigoB2C, $log_event);
                        $reply = array('status'=>false, 'error'=>$log_event);
                }
            } else {
                $log_status = 'fail';
                $log_event = 'curl error:'.curl_errno($ch);
                Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                $reply = array('status'=>false, 'error'=>'Curl error[Error code:' . curl_errno($ch) . ']');
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

    public static function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    /**
     * @param $phoneNumber
     * @return array
     */
    public function confirmReceiverNumber($phoneNumber){

        if (!is_numeric($phoneNumber)){
            return array('status'=>false,'error'=>'Not numeric number');
        }

        if ((strlen($phoneNumber) == 10)){
            if($this->startsWithZero($phoneNumber,'0')){
                $phoneNumber = '255'.substr($phoneNumber,1,10);
                return array('status'=>true,'number'=> $phoneNumber);
            } else {
                return array('status'=>false,'error'=>'Number does not start with zero');
            }
        } else if((strlen($phoneNumber) == 12)) {
            return array('status'=>true,'number'=>$phoneNumber);
        } else{
            return array('status'=>false,'error'=>'10 or 12 digits number required');
        }
    }


    public function createTigoB2CModel($merchantPayment)
    {
        $tigoB2C = TigoB2C::create([
            'type' => config('payments.tigo.bc2.type'),
            'reference_id' => $merchantPayment->payment_ref,
            'msisdn' => config('payments.tigo.bc2.mfi'),
            'pin' => config('payments.tigo.bc2.pin'),
            'msisdn1' => $merchantPayment->merchantPaymentAccount->account_number,
            'amount' => $merchantPayment->merchant_amount,
            'merchant_payment_id' => $merchantPayment->id,
            'language' => config('payments.tigo.bc2.language'),
        ]);
        return $tigoB2C;
    }

    /**
     * @param TigoB2C $tigoB2C
     * @param string $reason
     */
    private function deleteTigoB2CTransactionModel(TigoB2C $tigoB2C, $reason=''){
        Log::channel('mpesab2c')->error('TigoB2C model record has been deleted#reason:'.$reason . PHP_EOL);
            /*$tigoB2C->txn_status = TigoB2C::ERROR_CODE_001;
            $tigoB2C->txn_message = TigoB2C::ERROR_DESC_001;*/
            $tigoB2C->delete();
    }

    /**
     * @param $input
     * @param $tigoB2C
     */
    private function onTransferSuccess($input, $tigoB2C): void
    {
        $tigoB2C->transaction_status = TigoB2C::TRANS_STATUS_SETTLED;
        $tigoB2C->txn_id = $input['TXNID'];
        $tigoB2C->txn_status = $input['TXNSTATUS'];
        $tigoB2C->txn_message = $input['MESSAGE'];
        $tigoB2C->update();
    }

    /**
     * @param $input
     * @param $tigoB2C
     * @return mixed
     */
    private function onTransferFailure($input, $tigoB2C)
    {
        $tigoB2C->transaction_status = TigoB2C::TRANS_STATUS_FAILED;
        $tigoB2C->txn_status = isset($input['TXNSTATUS']) ? $input['TXNSTATUS'] : 'null';
        $tigoB2C->txn_message = isset($input['MESSAGE']) ? $input['MESSAGE'] : 'null';
        $tigoB2C->update();
        return $input;
    }

    /**
     * @param TigoB2C $tigoB2C
     * @param $status
     */
    public function setTigoB2CTransactionStatus(TigoB2C $tigoB2C, $status){
        $tigoB2C->transaction_status = $status;
        $tigoB2C->update();
    }

    /**
     * @param $tigoB2C
     * @param $input
     * @param $log_data
     * @return array
     */
    protected function verifyTigoB2CResponse($tigoB2C, $input, $log_data): array
    {
        $log_action = 'Verify tigo b2c post response';
        $log_format_fail = '%s,%s,%s,%s';
        $log_format_success = '%s,%s,%s';

        if (isset($input['TXNID'])) {
            Log::info(sprintf($log_format_success,$log_action,'success','reference:'.$tigoB2C->reference_id). PHP_EOL);
            $this->onTransferSuccess($input, $tigoB2C);
            $reply = array('status' => true, 'model' => $tigoB2C, 'response' => $input);

        } else {
            Log::error(sprintf($log_format_fail,$log_action,'fail','TXNID not set',$log_data). PHP_EOL);
            $input = $this->onTransferFailure($input, $tigoB2C);
            $reply = array('status' => false, 'error' => $input);
        }
        return $reply;
    }
}