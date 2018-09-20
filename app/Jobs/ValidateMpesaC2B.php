<?php

namespace App\Jobs;

use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\Mpesa\Mpesa;
use Illuminate\Bus\Queueable;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class ValidateMpesaC2B implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AuthorizeBooking;

    private $request;
    private $mpesa;


    public function __construct($request, Mpesa $mpesa)
    {
        $this->request = $request;
        $this->mpesa = $mpesa;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {

        try {
            $isValidated = $this->mpesa->validatePaymentC2B([
                'amount' => $this->request['request']['transaction']['amount'],
                'account_reference' => $this->request['request']['transaction']['accountReference'],
                'command_id' => $this->request['request']['transaction']['commandID'],
                'initiator' => $this->request['request']['transaction']['initiator'],
                'og_conversation_id' => $this->request['request']['transaction']['originatorConversationID'],
                'recipient' => $this->request['request']['transaction']['recipient'],
                'mpesa_receipt' => $this->request['request']['transaction']['mpesaReceipt'],
                'transaction_date' => $this->request['request']['transaction']['transactionDate'],
                'transaction_id' => $this->request['request']['transaction']['transactionID'],
                'conversation_id' => $this->request['request']['transaction']['conversationID'],
            ]);

            if ($isValidated['status']) {
                //Stop the booking timer
                $mpesaC2B = $isValidated['mpesaC2B'];

                if ($this->mpesa->processPaymentC2B($mpesaC2B)) {
                    $bookingPayment = $mpesaC2B->bookingPayment()->first();
                    $this->setBookingAuthorized($bookingPayment->booking()->first());
                    ConfirmMpesaC2B::dispatch($mpesaC2B, $bookingPayment);
                }
            }


        } catch (Exception $ex){
            Log::channel('mpesac2b')->error('Failed to validate transaction['.$ex->getMessage().']'. PHP_EOL );
            return false;
        }

        return true;
    }
}
