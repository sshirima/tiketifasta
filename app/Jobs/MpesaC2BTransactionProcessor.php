<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\Bookings\AuthorizeBooking;
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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AuthorizeBooking, MpesaTransactionC2B, TicketManager;

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
            $validation = $this->validateMpesaC2BTransaction($this->request);

            if ($validation['status']) {
                //Stop the booking timer
                $mpesaC2B = $validation['mpesaC2B'];

                $this->generateServiceNumber($mpesaC2B);

                $bookingPayment = $mpesaC2B->bookingPayment()->first();

                $booking = $bookingPayment->booking()->first();

                $this->setBookingConfirmed($booking);

                $ticket = $this->createTicket($bookingPayment);

                $confirmation = $this->createMpesaC2BConfirmRequest($booking, $mpesaC2B, $ticket);

                if($confirmation['status']){

                    $response = $confirmation['response'];

                    if ($this->verifyMpesaC2BResponse($mpesaC2B, $response)) {

                        $this->setMpesaC2BStatusConfirmed($mpesaC2B);

                        $booking->confirmBooking();

                        $this->confirmTicket($ticket);
                    }
                }

                //ConfirmMpesaC2B::dispatch($mpesaC2B, $bookingPayment);
            }


        } catch (Exception $ex){
            Log::channel('mpesac2b')->error('Failed to validate transaction['.$ex->getMessage().']'. PHP_EOL );
            return false;
        }

        return true;
    }


}
