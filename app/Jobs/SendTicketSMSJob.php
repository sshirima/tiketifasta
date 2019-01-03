<?php

namespace App\Jobs;

use App\Mail\RegisterMerchantMail;
use App\Models\Merchant;
use App\Models\Staff;
use App\Services\SMS\SendSMS;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendTicketSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, SendSMS;

    protected $ticket;
    protected $transaction;
    /**
     * SendTicketSMSJob constructor.
     * @param $ticket
     * @param $transaction
     */
    public function __construct($ticket, $transaction)
    {
        $this->ticket = $ticket;
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendConfirmationMessageToCustomer($this->ticket, $this->transaction);
    }
}
