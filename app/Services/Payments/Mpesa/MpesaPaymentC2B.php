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
use App\Services\Payments\Mpesa\xml\MpesaC2BData;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Log;
use Nathanmac\Utilities\Parser\Parser;

trait MpesaPaymentC2B
{
    use MpesaC2BData;

    public function initializePaymentC2B(array $attributes)
    {
        return MpesaC2B::create($attributes);
    }

    public function getMpesaC2BValidationResponse(Request $request){
        $parser = new Parser();
        $input = $parser->xml($request->getContent());
        return $this->mpesaC2BValidationResponse([
            'conversationID' => $input['request']['transaction']['conversationID'],
            'originatorConversationID' => $input['request']['transaction']['originatorConversationID'],
            'responseCode' => 0,
            'responseDesc' => 'Received',
            'serviceStatus' => 'Success',
            'transactionID' => $input['request']['transaction']['transactionID'],
        ]);
    }

    public function validateMpesaC2BTransaction(array $request):array
    {
        $attributes = [
            'amount' => $request['request']['transaction']['amount'],
            'account_reference' => $request['request']['transaction']['accountReference'],
            'command_id' => $request['request']['transaction']['commandID'],
            'initiator' => $request['request']['transaction']['initiator'],
            'og_conversation_id' => $request['request']['transaction']['originatorConversationID'],
            'recipient' => $request['request']['transaction']['recipient'],
            'mpesa_receipt' => $request['request']['transaction']['mpesaReceipt'],
            'transaction_date' => $request['request']['transaction']['transactionDate'],
            'transaction_id' => $request['request']['transaction']['transactionID'],
            'conversation_id' => $request['request']['transaction']['conversationID'],
        ];

        $mpesaC2B = MpesaC2B::where(['account_reference'=>$attributes['account_reference']])->first();

        if (!isset($mpesaC2B)) {
            Log::channel('mpesac2b')->info('Transaction not found:'.'['.$attributes['mpesa_receipt'].']'. PHP_EOL );
            return array('status'=>false,'message'=>'Transaction not found');
        }

        if ($this->isDuplicateC2B($attributes['mpesa_receipt'])) {
            Log::channel('mpesac2b')->info('Transaction is duplicated:'.'['.$attributes['mpesa_receipt'].']'. PHP_EOL );
            return array('status'=>false,'message'=>'Transaction is duplicated');
        }

        if ($mpesaC2B->amount != $attributes['amount']) {
            Log::channel('mpesac2b')->info('Paid amount is not equal to ticket price:'.'['. 'Paid='.$attributes['amount'].']'. PHP_EOL );
            return array('status'=>false,'message'=>'Paid amount is not equal to ticket price');
        }

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
        
        return array('status'=>$mpesaC2B->update(),'mpesaC2B'=>$mpesaC2B);
    }

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

            $url = env('MPESA_C2B_CONFIRM');
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

            $response = curl_exec($ch);
            //Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        Log::channel('mpesac2b')->info('Transaction confirmed' . PHP_EOL);
                        $reply = ['status'=>true, 'response'=>$response];
                        break;
                    default:
                        Log::channel('mpesac2b')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                        $reply = ['status'=>false, 'error'=>'Unexpected HTTP code: ' . $http_code . '[' . $response . ']'];
                }
            } else {
                Log::channel('mpesac2b')->error('Curl error[Error code:' . curl_errno($ch) . ']' . PHP_EOL);
                $reply = ['status'=>true, 'response'=>'Curl error[Error code:' . curl_errno($ch) . ']'];
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
        $mpesa = new Mpesa();
        $timestamp = PaymentManager::getCurrentTimestamp();
        $spPassword = $mpesa->encryptSPPassword(env('MPESA_SPID'), env('MPESA_PASSWORD'), $timestamp);

        return $this->c2bPaymentConfirmRequest([
            'spId' => env('MPESA_SPID'),
            'spPassword' => $spPassword,
            'timestamp' => $timestamp,
            'resultType' => 'Completed',
            'resultCode' => 0,
            'resultDesc' => 'Successful',
            'serviceReceipt' => $ticket->ticket_ref,//Ticket receipt
            'serviceDate' => date('Y-m-d H:i:s'),//Ticket ID
            'serviceID' => $ticket->id,//Ticket ID
            'originatorConversationID' => $mpesaC2B->og_conversation_id,//Ticket ID
            'conversationID' => $mpesaC2B->conversation_id,//Ticket ID
            'transactionID' => $mpesaC2B->transaction_id,//Ticket ID
            'initiator' => null,//$this->mpesaC2B->reference,//Ticket ID
            'initiatorPassword' => null, //$this->mpesaC2B->reference,//Ticket ID
        ]);
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

        if (isset($mpesaC2B)) {
            return true;
        } else {
            return false;
        }
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
        $parser = new Parser();
        $input = $parser->xml($response);

        return $mpesaC2B->og_conversation_id == $input['response']['originatorConversationID']
            && $input['response']['serviceStatus'] == 'Confirming' && $input['response']['transactionID'] == $mpesaC2B->transaction_id;
    }
}