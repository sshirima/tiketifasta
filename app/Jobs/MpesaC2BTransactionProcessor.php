<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\MpesaC2B;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\BookingPayments\BookingPaymentProcessor;
use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Mpesa\MpesaTransactionC2B;
use App\Services\Tickets\TicketManager;
use Illuminate\Bus\Queueable;
use Exception;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class MpesaC2BTransactionProcessor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AuthorizeBooking, BookingPaymentProcessor;

    private $request;


    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return boolean
     */
    public function handle()
    {

        try {
            $validation = $this->validateMpesaC2BTransaction($this->request);

            if(!$validation['status']){

                $this->cancelMpesaC2BTransaction();

                return false;
            }

            $mpesaC2B = $validation['mpesaC2B'];

            $bookingPayment = $mpesaC2B->bookingPayment;

            $booking = $bookingPayment->booking;

            $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_AUTHORIZED);

            $this->setBookingAuthorized($booking);

            $this->generateServiceNumber($mpesaC2B);

            $ticket = $this->processTicket($mpesaC2B);

            $confirmation = $this->postMpesaC2BTransaction($mpesaC2B, $ticket);

            if(!$confirmation['status']){
                $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_FAILED);
                Log::channel('mpesac2b')->error('Mpesa transaction confirmation failed:mpesaReceipt='.$mpesaC2B->mpesa_receipt.PHP_EOL );
                return false;
            }

            $response = $confirmation['response'];

            if (!($this->verifyMpesaC2BResponse($mpesaC2B, $response))) {
                $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_FAILED);
                Log::channel('mpesac2b')->error('Confirmation response failed'.PHP_EOL );
                return false;
            }

            $this->setMpesaC2BStatusConfirmed($mpesaC2B);

            $this->setBookingConfirmed($booking);

            $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_SETTLED);

            $this->confirmTicket($ticket, $bookingPayment);

        } catch (Exception $ex){

            Log::channel('mpesac2b')->error('Process mpesa c2b transaction, fail, exception: '.$ex->getTraceAsString(). PHP_EOL );
            return false;
        }

        return true;
    }

    /**
     *
     */
    private function cancelMpesaC2BTransaction()
    {
        $attributes = $this->getMpesaC2BAuthorizationParamsArray($this->request);

        $cancellation_request = $this->postMpesaC2BTransaction(null, null, $attributes);

        if (!$cancellation_request['status']) {
            Log::error('Canceling mpesa c2b transaction failed, error:'.$cancellation_request['error'] . PHP_EOL);
        } else {
            $response = $cancellation_request['response'];

            Log::info('Cancelling mpesa c2b transaction success, transaction_id:'.$response['response']['transactionID'] . PHP_EOL);
        }
    }


}
