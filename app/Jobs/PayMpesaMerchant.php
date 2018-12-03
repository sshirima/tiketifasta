<?php

namespace App\Jobs;

use App\Models\MerchantPayment;
use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use App\Services\Payments\Mpesa\MpesaTransactionB2C;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PayMpesaMerchant implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MpesaTransactionB2C, MerchantPaymentProcessor;

    protected $merchantPayment;


    public function __construct(MerchantPayment $merchantPayment)
    {
        $this->merchantPayment = $merchantPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $account = $this->merchantPayment->merchantPaymentAccount()->first();

        $response = $this->initializeMpesaB2CTransaction($account->account_number, $this->merchantPayment->merchant_amount);

        if ($response['status']){

            $this->setMerchantPaymentId($response['model'], $this->merchantPayment->id);

            $this->onMerchantPaymentInitiated($this->merchantPayment);

        }else {
            $this->onMerchantPaymentFailure($this->merchantPayment);
        }
    }
}
