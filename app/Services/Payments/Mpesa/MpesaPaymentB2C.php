<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:28 PM
 */

namespace App\Services\Payments\Mpesa;


use App\Models\MpesaB2C;
use App\Services\Payments\Mpesa\xml\MpesaB2CData;
use Nathanmac\Utilities\Parser\Parser;
use Log;

trait MpesaPaymentB2C
{

    use MpesaB2CData;

    public function initializeB2CPayment(MpesaB2C $mpesaB2C){

        $returnData = null;

        $requestBody = $this->getBodyContent($mpesaB2C);

        $url = 'https://41.217.203.241:27443/broker/transfer';
        $ch = curl_init();
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
        $response = curl_exec($ch);
        //dd(curl_getinfo($ch, CURLINFO_HTTP_CODE));
        //Check HTTP status code
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:
                    //Confirm the transaction, set booking and ticket  confirmed send notification to user
                    Log::channel('mpesab2c')->info('B2C transaction initiated' . PHP_EOL);
                    $parser = new Parser();
                    $returnData = $parser->xml($response);
                    break;
                default:
                    Log::channel('mpesab2c')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                    $returnData = $response;
                //echo 'Unexpected HTTP code: ', $http_code, "\n";
            }
        } else {
            Log::channel('mpesab2c')->error('Curl error[ Error code:' . curl_errno($ch) . ']' . PHP_EOL);
            $returnData = $response;
        }
        curl_close($ch);

        return $returnData;
    }

    public function createB2CTransaction($values){
        return MpesaB2C::create($values);
    }

    private function getBodyContent($mpesaB2C){
        $mpesa = new Mpesa();
        $timestamp = date('YmdHis');
        $spPassword = $mpesa->encryptSPPassword(env('MPESA_SPID'), env('MPESA_PASSWORD'), $timestamp);
        $initiator = env('MPESA_B2C_INITIATOR');
        $initiatorPassword = $mpesa->encryptInitiatorPassword(env('MPESA_B2C_INITIATOR_PASSWORD'));

        return $this->c2bPaymentConfirmRequest([
            'spId' => env('MPESA_SPID'),
            'spPassword' => $spPassword,
            'timestamp' => $timestamp,
            'amount' => $mpesaB2C->amount,
            'commandID' => $mpesaB2C->commandID,
            'initiator' => $initiator,
            'initiatorPassword' => $initiatorPassword,
            'recipient' => $mpesaB2C->recipient,
            'transactionDate' => $mpesaB2C->transaction_date,
            'transactionID' => $mpesaB2C->transaction_id,
        ]);
    }
}