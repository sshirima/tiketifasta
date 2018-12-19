<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MerchantPaymentSeederTable extends Seeder
{

    use \App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $response = $this->processPayments('2018-12-17');

        print 'INFO: Task status: '.json_encode($response).PHP_EOL;
        print '=================================='.PHP_EOL;
    }

    public function payMerchant(\App\Models\MerchantPayment $payment)
    {
        $report = array('status' => false,'error'=>'');

        if ($payment->payment_mode == 'mpesa') {

            $report = $this->issueMpesaPayments($payment);

        }

        if ($payment->payment_mode == 'tigopesa') {

            $report = $this->issueTigoPesaPayment($payment);
        }

        $payment->payment_stage = 'TRANSFER_SUCCESS';
        $payment->transaction_status = \App\Models\MerchantPayment::TRANS_STATUS_SETTLED;
        $payment->transfer_status = true;
        $payment->update();

        return $report;
    }

    public function issueMpesaPayments(\App\Models\MerchantPayment $merchantPayment)
    {
        $this->createMpesaB2CModel($merchantPayment);
    }

    public function issueTigoPesaPayment(\App\Models\MerchantPayment $merchantPayment)
    {
        $this->createTigoB2CModel($merchantPayment);
    }

    public function createMpesaB2CModel(\App\Models\MerchantPayment $merchantPayment)
    {
        $mpesaB2C = \App\Models\MpesaB2C::create($this->getMpesaB2CParametersArray($merchantPayment));

        $mpesaB2C->mpesa_receipt = strtoupper(\App\Services\Payments\PaymentManager::random_code(8));
        $mpesaB2C->conversation_id = \App\Services\Payments\PaymentManager::random_code(16);
        $mpesaB2C->status = \App\Models\MpesaB2C::STATUS_LEVEL[2];
        $mpesaB2C->transaction_status = \App\Models\MpesaB2C::TRANS_STATUS_SETTLED;
        $mpesaB2C->update();
    }

    public function createTigoB2CModel($merchantPayment)
    {
        $tigoB2C = \App\Models\TigoB2C::create([
            'type' => config('payments.tigo.bc2.type'),
            'reference_id' => $merchantPayment->payment_ref,
            'msisdn' => config('payments.tigo.bc2.mfi'),
            'pin' => config('payments.tigo.bc2.pin'),
            'msisdn1' => $merchantPayment->merchantPaymentAccount->account_number,
            'amount' => $merchantPayment->merchant_amount,
            'merchant_payment_id' => $merchantPayment->id,
            'language' => config('payments.tigo.bc2.language'),
        ]);

        $tigoB2C->transaction_status = \App\Models\TigoB2C::TRANS_STATUS_SETTLED;
        $tigoB2C->txn_id = mt_rand(1000000, 9999999);
        $tigoB2C->txn_status = '0';
        $tigoB2C->txn_message = 'Success';
        $tigoB2C->update();

        return $tigoB2C;

    }

    public function getMpesaB2CParametersArray(\App\Models\MerchantPayment $merchantPayment): array
    {
        $account = $merchantPayment->merchantPaymentAccount;

        $receipt = $account->account_number;
        $amount = $merchantPayment->merchant_amount;

        return [
            'amount' => $amount,
            'command_id' => 'BusinessPayment',
            'initiator' => config('payments.mpesa.b2c.initiator'),
            'recipient' => $receipt,
            'merchant_payment_id' => $merchantPayment->id,
            'og_conversation_id' => strtoupper(\App\Services\Payments\PaymentManager::random_code(16)),
            'transaction_date' => \App\Services\Payments\PaymentManager::getCurrentTimestamp(),
            'transaction_id' => $merchantPayment->payment_ref,
        ];
    }
}
