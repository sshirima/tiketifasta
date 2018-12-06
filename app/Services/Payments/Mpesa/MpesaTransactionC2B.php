<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:27 PM
 */

namespace App\Services\Payments\Mpesa;


use App\Models\Booking;
use App\Models\MpesaC2B;
use App\Models\Ticket;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Log;
use Nathanmac\Utilities\Parser\Parser;

trait MpesaTransactionC2B
{
    use MpesaTransactionC2BRequest;

    public function createMpesaC2B($bookingPayment){
        MpesaC2B::create($this->mpesaC2BParametersArray($bookingPayment));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getMpesaC2BValidationResponse(Request $request){

        $parser = new Parser();

        $input = $parser->xml($request->getContent());

        return $this->c2bValidationResponseToXml($this->getValidationReponseArray($input));
    }

    /**
     * @param array $request
     * @return array
     */
    public function validateMpesaC2BTransaction(array $request):array
    {
        $attributes = $this->extractParamsValidationRequest($request);

        $mpesaC2B = MpesaC2B::where(['account_reference'=>$attributes['account_reference']])->first();

        if (!isset($mpesaC2B)) {
            Log::channel('mpesac2b')->error('Transaction not found:'.'['.$attributes['mpesa_receipt'].']'. PHP_EOL );
            return array('status'=>false,'error'=>'Transaction not found');
        }

        if ($this->isDuplicateC2B($attributes['mpesa_receipt'])) {
            Log::channel('mpesac2b')->error('Transaction is duplicated:'.'['.$attributes['mpesa_receipt'].']'. PHP_EOL );
            return array('status'=>false,'error'=>'Transaction is duplicated');
        }

        if ($mpesaC2B->amount != $attributes['amount']) {
            Log::channel('mpesac2b')->error('Paid amount is not equal to ticket price:'.'['. 'Paid='.$attributes['amount'].']'. PHP_EOL );
            return array('status'=>false,'error'=>'Paid amount is not equal to ticket price');
        }

        $this->updateValidationParameters($attributes, $mpesaC2B);
        
        return array('status'=>$mpesaC2B->update(),'mpesaC2B'=>$mpesaC2B);
    }

    /**
     * @param MpesaC2B $mpesaC2B
     * @return bool
     */
    public function generateServiceNumber(MpesaC2B $mpesaC2B)
    {
        //Generate service number
        $mpesaC2B->service_receipt = strtoupper(PaymentManager::random_code(8));
        $mpesaC2B->stage = '1';

        return $mpesaC2B->update();
    }

    /**
     * @param Booking $booking
     * @param MpesaC2B $mpesaC2B
     * @param Ticket $ticket
     * @return array
     */
    public function createMpesaC2BConfirmRequest(Booking $booking, MpesaC2B $mpesaC2B, Ticket $ticket){

        try{

            $url = config('payments.mpesa.c2b.confirm_transaction_url');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_SSLKEY, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.key');
            curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/storage/mpesa/root.pem');
            curl_setopt($ch, CURLOPT_SSLCERT, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.cer');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getMpesaC2BRequestParams($ticket, $mpesaC2B));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_TIMEOUT, config('payments.mpesa.b2c.timeout'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('payments.mpesa.b2c.connect_timeout'));

            $xmlResponse = curl_exec($ch);

            if ($xmlResponse === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    Log::channel('mpesab2c')->error('Connection timeout: url='.$url  . PHP_EOL);
                }
            }
            //Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        Log::channel('mpesac2b')->info('Error on the response: response='.$xmlResponse . PHP_EOL);
                        /*$parser = new Parser();
                        $input = $parser->xml($response);*/
                        $objectResponse = simplexml_load_string($xmlResponse);
                        $jsonResponse =json_encode($objectResponse);
                        $reply = ['status'=>true, 'response'=>$jsonResponse];
                        break;
                    default:
                        Log::channel('mpesac2b')->error('Unexpected HTTP code: ' . $http_code . '[' . $xmlResponse . ']' . PHP_EOL);
                        $reply = ['status'=>false, 'error'=>'Unexpected HTTP code: ' . $http_code . '[' . $xmlResponse . ']'];
                }
            } else {
                Log::channel('mpesac2b')->error('Curl error[Error code:' . curl_errno($ch) . ']' . PHP_EOL);
                $reply = ['status'=>false, 'response'=>'Curl error[Error code:' . curl_errno($ch) . ']'];
            }

            curl_close($ch);
        }catch (\Exception $exception){
            Log::channel('mpesac2b')->error('Error:' . $exception->getMessage() . PHP_EOL);
            $reply = ['status'=>true, 'response'=>'Error:' . $exception->getMessage()];
        }

        return $reply;
    }

    /**
     * @param $ticket
     * @param $mpesaC2B
     * @return mixed
     */
    private function getMpesaC2BRequestParams($ticket, $mpesaC2B)
    {
        $timestamp = date('YmdHis');

        $spPassword = $this->encryptSPPassword(env('MPESA_SPID'), env('MPESA_PASSWORD'), $timestamp);

        return $this->c2bConfirmRequestToXml($this->getMpesaC2BConfirmRequestArray($ticket, $mpesaC2B, $spPassword, $timestamp));
    }

    /**
     * @param $spId
     * @param $spPassword
     * @param $timestamp
     * @return string
     */
    protected function encryptSPPassword($spId, $spPassword, $timestamp)
    {
        //return base64_encode(hash("sha256", $spId.$spPassword.$timestamp, true));
        return base64_encode(strtoupper(hash('sha256', $spId.$spPassword.$timestamp)));

    }

    /**
     * @param MpesaC2B $mpesaC2B
     */
    public function setMpesaC2BStatusConfirmed(MpesaC2B $mpesaC2B)
    {
        //Stage 0
        $mpesaC2B->stage ='0';
        $mpesaC2B->service_status = 'confirmed';
        $mpesaC2B->update();
    }

    public function cancelPaymentC2B(MpesaC2B $mpesaC2B)
    {
        //Stage 0
        $mpesaC2B->service_status = 'cancelled';
        $mpesaC2B->update();
    }

    /**
     * @param $mpesaId
     * @return bool
     */
    public function isDuplicateC2B($mpesaId)
    {
        $mpesaC2B = MpesaC2B::where(['mpesa_receipt' => $mpesaId])->first();

       return isset($mpesaC2B);
    }

    /**
     * @param $limit
     * @return bool|string
     */
    public function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    /**
     * @param $mpesaC2B
     * @param $response
     * @return bool
     */
    public function verifyMpesaC2BResponse($mpesaC2B, $response): bool
    {
        /*$parser = new Parser();
        $input = $parser->xml($response);*/

        return $mpesaC2B->og_conversation_id == $response['response']['originatorConversationID']
            && $response['response']['serviceStatus'] == 'Confirming' && $response['response']['transactionID'] == $mpesaC2B->transaction_id;
    }

    /**
     * @param $attributes
     * @param $mpesaC2B
     */
    protected function updateValidationParameters($attributes, $mpesaC2B): void
    {
        $mpesaC2B->command_id = $attributes['command_id'];
        $mpesaC2B->initiator = $attributes['initiator'];
        $mpesaC2B->og_conversation_id = $attributes['og_conversation_id'];
        $mpesaC2B->recipient = $attributes['recipient'];
        $mpesaC2B->mpesa_receipt = $attributes['mpesa_receipt'];
        $mpesaC2B->transaction_date = $attributes['transaction_date'];
        $mpesaC2B->transaction_id = $attributes['transaction_id'];
        $mpesaC2B->conversation_id = $attributes['conversation_id'];
        $mpesaC2B->authorized_at = date('Y-m-d H:i:s');
        $mpesaC2B->stage = '2';
    }

    /**
     * @param $bookingPayment
     * @return array
     */
    protected function mpesaC2BParametersArray($bookingPayment): array
    {
        return [
            'msisdn' => $bookingPayment->phone_number,
            'amount' => $bookingPayment->amount,
            'account_reference' => $bookingPayment->payment_ref,
            'booking_payment_id' => $bookingPayment->id,
        ];
    }
}