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
use Log;
use Nathanmac\Utilities\Parser\Parser;

trait MpesaPaymentC2B
{
    use MpesaC2BData;

    public function initializePaymentC2B(array $attributes)
    {
        //Stage 3 create transaction reference, msisdn, amount, reference
        return MpesaC2B::create($attributes);

    }

    public function validatePaymentC2B(array $attributes):array
    {
        //Stage 2, fetch by account reference

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

    public function confirmPayment(Booking $booking, MpesaC2B $mpesaC2B, Ticket $ticket){
        $url = env('MPESA_C2B_CONFIRM');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLKEY, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.key');
        curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/storage/mpesa/root.pem');
        curl_setopt($ch, CURLOPT_SSLCERT, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.cer');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getBodyContent($ticket, $mpesaC2B));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        //Check HTTP status code
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:
                    //Confirm the transaction, set booking and ticket  confirmed send notification to user
                    Log::channel('mpesac2b')->info('Transaction confirmed' . PHP_EOL);
                    $parser = new Parser();
                    $input = $parser->xml($response);

                    if ($mpesaC2B->og_conversation_id == $input['response']['originatorConversationID']
                        && $input['response']['serviceStatus'] == 'Confirming' && $input['response']['transactionID'] == $mpesaC2B->transaction_id) {
                        $mpesa = new Mpesa();
                        $mpesa->confirmPaymentC2BTransaction($mpesaC2B);
                        $booking = $ticket->booking()->first();
                        $booking->confirmBooking();
                        $this->confirmTicket($ticket);
                    }
                    //echo $input;
                    break;
                default:
                    Log::channel('mpesac2b')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                //echo 'Unexpected HTTP code: ', $http_code, "\n";
            }
        } else {
            Log::channel('mpesac2b')->error('Curl error[Error code:' . curl_errno($ch) . ']' . PHP_EOL);
            //echo curl_errno($ch);
        }
        curl_close($ch);
    }

    public function processPaymentC2B(MpesaC2B $mpesaC2B)
    {
        //Generate service number
        $mpesaC2B->service_receipt = strtoupper(PaymentManager::random_code(8));
        $mpesaC2B->stage = '1';

        return $mpesaC2B->update();
    }

    public function confirmPaymentC2BTransaction(MpesaC2B $mpesaC2B)
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

    public function isDuplicateC2B($mpesaId)
    {
        $mpesaC2B = MpesaC2B::where(['mpesa_receipt' => $mpesaId])->first();

        if (isset($mpesaC2B)) {
            return true;
        } else {
            return false;
        }
    }

    function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}