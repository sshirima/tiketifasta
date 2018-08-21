<?php

namespace App\Jobs;

use App\Models\MpesaC2B;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\Mpesa\Mpesa;
use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Nathanmac\Utilities\Parser\Parser;

class ValidateMpesaC2B implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AuthorizeBooking;

    private $request;


    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mpesa = new Mpesa();


        $isValidated = $mpesa->validatePaymentC2B([
            'amount'=>$this->request['request']['transaction']['amount'],
            'account_reference'=>$this->request['request']['transaction']['accountReference'],
            'command_id'=>$this->request['request']['transaction']['commandID'],
            'initiator'=>$this->request['request']['transaction']['initiator'],
            'og_conversation_id'=>$this->request['request']['transaction']['originatorConversationID'],
            'recipient'=>$this->request['request']['transaction']['recipient'],
            'mpesa_receipt'=>$this->request['request']['transaction']['mpesaReceipt'],
            'transaction_date'=>$this->request['request']['transaction']['transactionDate'],
            'transaction_id'=>$this->request['request']['transaction']['transactionID'],
            'conversation_id'=>$this->request['request']['transaction']['conversationID'],
        ]);

        if($isValidated['status'] ){
            //Stop the booking timer
            $mpesaC2B = $isValidated['mpesaC2B'];

            if($mpesa->processPaymentC2B($mpesaC2B)){
                $bookingPayment = $mpesaC2B->bookingPayment()->first();
                $this->setBookingAuthorized($bookingPayment->booking()->first());

                ConfirmMpesaC2B::dispatch($mpesaC2B, $bookingPayment);
            }
        }
    }
}
