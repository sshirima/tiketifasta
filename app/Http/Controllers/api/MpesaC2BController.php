<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/13/2018
 * Time: 10:13 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Jobs\MpesaC2BTransactionProcessor;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Mpesa\MpesaTransactionC2B;
use App\Services\Tickets\TicketManager;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;
use Exception;
use Log;

class MpesaC2BController extends Controller
{
    use AuthorizeBooking, TicketManager, MpesaTransactionC2B;
    private $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function validateMpesaC2BTransaction(Request $request)
    {
        Log::channel('mpesac2b')->info('Received mpesa C2B callback'  . PHP_EOL);
        try {
            $parser = new Parser();
            $input = $parser->xml($request->getContent());

            $response = $this->getMpesaC2BValidationResponse($request);

            MpesaC2BTransactionProcessor::dispatch($input);

        } catch (Exception $ex) {
            return json_encode($ex->getMessage());//response($response, 200, ['Content-type'=>'application/xml']);
        }

        return response($response, 200, ['Content-type' => 'application/xml']);
    }

    /**
     * @param Request $request
     */
    /*public function confirmPaymentC2B(Request $request)
    {
        try {
            $bookingPayment = BookingPayment::find($request->all()['payment_id']);

            $mpesaC2B = $bookingPayment->mpesaC2B()->first();

            $ticket = $this->createTicket($bookingPayment);

            $url = config('payments.mpesa.c2b.confirm_payment_url');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($ch, CURLOPT_SSLKEY, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.key');
            curl_setopt($ch, CURLOPT_CAINFO, '/var/www/html/storage/mpesa/root.pem');
            curl_setopt($ch, CURLOPT_SSLCERT, '/var/www/html/storage/mpesa/tkj.vodacom.co.tz.cer');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->getParametersConfirmResponse($ticket, $mpesaC2B));
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
                            $this->mpesa->setMpesaC2BStatusConfirmed($mpesaC2B);
                            $booking = $ticket->booking()->first();
                            $booking->confirmBooking();
                            $this->confirmTicket($ticket);
                        }
                        echo json_encode($input);
                        break;
                    default:
                        Log::channel('mpesac2b')->error('Unexpected HTTP code: ' . $http_code . '[' . $response . ']' . PHP_EOL);
                        echo 'Unexpected HTTP code: ', $http_code, "\n";
                }
            } else {
                Log::channel('mpesac2b')->error('Curl error[Error code:'.curl_errno($ch).']' . PHP_EOL);
                echo curl_errno($ch);
            }
            curl_close($ch);
        } catch (Exception $ex) {
            Log::channel('mpesac2b')->error('Failed to confirm transaction: '.$ex->getMessage() . PHP_EOL);
            echo 'Error: ', $ex->getMessage(), "\n";
        }
    }

    private function getParametersConfirmResponse($ticket, $mpesaC2B)
    {
        $mpesa = new Mpesa();
        $timestamp = PaymentManager::getCurrentTimestamp();
        $spPassword = $mpesa->encryptSPPassword(env('MPESA_SPID'), env('MPESA_PASSWORD'), $timestamp);

        return $this->c2bConfirmRequestToXml([
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
    }*/
}
