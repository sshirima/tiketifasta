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
     * @param $msisdn
     * @param $amount
     * @return array
     */
    public function initializeTigoB2CTransaction($msisdn, $amount)
    {
        $reply = null;
        $ch = curl_init();
        try {
            //Create TigoB2C
            $tigoB2C = $this->createTigoB2CModel($msisdn, $amount);

            $requestContent = $this->b2cInitiatePaymentData($this->getParameterRequestArray($tigoB2C));

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
                    Log::channel('mpesab2c')->error('Connection timeout: url='.$url . PHP_EOL);
                    $this->deleteTigoB2CTransactionModel($tigoB2C, 'Connection timeout: url='.$url);
                }
            }

            //Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        //Confirm the transaction, set booking and ticket  confirmed send notification to user
                        Log::channel('tigoussdb2c')->info('Transaction confirmed' . PHP_EOL);
                        $parser = new Parser();
                        $input = $parser->xml($response);
                        //return array('status'=>true, 'model'=>$tigoB2C,'response'=>$input);
                        if (isset($input['TXNID'])){

                            $this->onTransferSuccess($input, $tigoB2C);

                            $reply = array('status'=>true, 'model'=>$tigoB2C,'response'=>$input);

                        } else {

                            $input = $this->onTransferFailure($input, $tigoB2C);

                            $reply = array('status'=>false, 'error'=>$input);
                        }
                        //echo $input;
                        break;
                    default:
                        Log::channel('tigoussdb2c')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                        $this->deleteTigoB2CTransactionModel($tigoB2C, 'Unexpected HTTP code: ' . $http_code);
                        $reply = array('status'=>false, 'error'=>'Unexpected HTTP code: ' . $http_code);
                    //echo 'Unexpected HTTP code: ', $http_code, "\n";
                }
            } else {
                Log::channel('tigoussdb2c')->error('Curl error[Error code:' . curl_errno($ch) . ']' . PHP_EOL);
                $reply = array('status'=>false, 'error'=>'Curl error[Error code:' . curl_errno($ch) . ']');
                //echo curl_errno($ch);
            }
        } catch (\Exception $ex) {
            $reply = array('status'=>false, 'error'=>$ex->getMessage());
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

    /**
     * @param $msisdn
     * @param $amount
     * @return mixed
     */
    public function createTigoB2CModel($msisdn, $amount)
    {
        $tigoB2C = TigoB2C::create([
            'type' => config('payments.tigo.bc2.type'),
            'reference_id' => strtoupper(self::random_code(8)),
            'msisdn' => config('payments.tigo.bc2.mfi'),
            'pin' => config('payments.tigo.bc2.pin'),
            'msisdn1' => $msisdn,
            'amount' => $amount,
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
}