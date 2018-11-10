<?php

namespace App\Jobs;

use App\Models\BookingPayment;
use App\Models\MpesaC2B;
use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Mpesa\xml\MpesaC2BData;
use App\Services\Payments\PaymentManager;
use App\Services\Tickets\TicketManager;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Exception;
use Nathanmac\Utilities\Parser\Parser;
use Log;

class ConfirmMpesaC2B implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MpesaC2BData, TicketManager;

    private $mpesaC2B;
    private $bookingPayment;


    public function __construct(MpesaC2B $mpesaC2B, BookingPayment $bookingPayment)
    {
        $this->mpesaC2B = $mpesaC2B;
        $this->bookingPayment = $bookingPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {


        } catch (Exception $ex) {
            Log::channel('mpesac2b')->error('Failed to confirm transaction[' . $ex->getMessage() . ']' . PHP_EOL);
        }
    }
}
