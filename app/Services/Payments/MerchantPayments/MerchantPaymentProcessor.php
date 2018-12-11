<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 11/21/2018
 * Time: 9:22 AM
 */

namespace App\Services\Payments\MerchantPayments;


use App\Models\BookingPayment;
use App\Models\MerchantPayment;
use App\Models\MerchantPaymentAccount;
use App\Services\Payments\PaymentManager;
use Illuminate\Support\Facades\DB;

trait MerchantPaymentProcessor
{
    use PayMerchant;
    /**
     * @param $date
     * @return array
     */
    public function processPayments($date)
    {

        $report = $this->generateDailyReport($date);

        $merchantPayments = $this->generateMerchantPayments($report);

        $payments = $this->makePayments($merchantPayments);

        $this->updateBookingPayments($merchantPayments, $date);

        return $payments;

    }

    /**
     * @param $date
     * @return \Illuminate\Support\Collection
     */
    protected function generateDailyReport($date)
    {

        $transactions = DB::table('schedules')->select('merchants.id as merchant_id', 'merchants.name as merchant_name',
            \DB::Raw('DATE(booking_payments.created_at) created_at'), 'booking_payments.method as payment_method', \DB::raw('sum(booking_payments.amount) as amount'))
            ->join('bookings', 'bookings.schedule_id', '=', 'schedules.id')
            ->join('buses', 'buses.id', '=', 'schedules.bus_id')
            ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
            ->join('days', 'days.id', '=', 'schedules.day_id')
            ->join('tickets', 'tickets.booking_id', '=', 'bookings.id')
            ->join('booking_payments', 'booking_payments.booking_id', '=', 'bookings.id')
            ->where(['booking_payments.transaction_status' => BookingPayment::TRANS_STATUS_SETTLED])
            ->whereNull('booking_payments.merchant_payment_id')
            ->whereDate('booking_payments.created_at', '=', $date)
            ->groupBy(\DB::raw("DATE(booking_payments.created_at)"), 'merchants.id', 'merchants.name', 'booking_payments.method')
            ->get();

        return $transactions;
    }

    /**
     * @param $transactions
     * @return array
     */
    protected function generateMerchantPayments($transactions)
    {

        $merchantPayments = array();
        foreach ($transactions as $transaction) {

            $merchantPayment = $this->createMerchantPaymentTransaction($transaction, $transaction->payment_method);

            $netAmount = $transaction->amount;
            $commissionAmount = $this->calculateCommissionedAmount($netAmount);
            $merchantAmount = $this->calculateMerchantAmount($netAmount, $commissionAmount);

            $this->setNetPaymentAmount($merchantPayment, $netAmount);
            $this->setMerchantPaymentAmount($merchantPayment, $merchantAmount);
            $this->setCommissionPaymentAmount($merchantPayment, $commissionAmount);

            $merchantPayments[] = $merchantPayment;
        }

        return $merchantPayments;
    }

    /**
     * @param $merchantPayments
     * @return array
     */
    protected function makePayments($merchantPayments)
    {

        $report = ['status' => false];

        try {
            foreach ($merchantPayments as $payment) {

                $report = $this->payMerchant($payment);
                if (!$report['status']) {
                    \Log::error('Erro#' . $report['error']);
                } else {
                    \Log::info('INFO: Success# Transfer to merchant account success');
                }
            }
        } catch (\Exception $exception) {
            $report = ['status' => false, 'error' => $exception->getMessage()];
        }

        return $report;
    }

    /**
     * @param $accountId
     * @param $date
     * @return mixed
     */
    protected function bookingPaymentsByAccountId($accountId, $date)
    {

        $condition = array();
        $condition[] = ['merchant_payment_accounts.id', '=', $accountId];
        //$condition[] = ['merchant_payment_accounts.payment_mode','=', $operator];


        return BookingPayment::select('booking_payments.id as id', 'merchants.id as merchant_id', 'booking_payments.payment_ref as reference',
            'booking_payments.amount as amount', 'booking_payments.method as payment_method',
            'merchant_payment_accounts.account_number as recipient', 'merchant_payment_accounts.payment_mode as account_payment_mode', 'merchant_payment_accounts.id as account_id')
            ->join('bookings', 'bookings.id', '=', 'booking_payments.booking_id')
            ->join('schedules', 'schedules.id', '=', 'bookings.schedule_id')
            ->join('buses', 'buses.id', '=', 'schedules.bus_id')
            ->join('merchants', 'merchants.id', '=', 'buses.merchant_id')
            ->join('merchant_payment_accounts', function ($join) {
                $join->on('merchant_payment_accounts.merchant_id', '=', 'merchants.id');
                $join->on('merchant_payment_accounts.payment_mode', '=', 'booking_payments.method');
            })
            ->where($condition)
            ->whereDate('booking_payments.created_at', '=', $date)->get();
    }

    /**
     * @param $actualAmount
     * @return int
     */
    private function calculateCommissionedAmount($actualAmount)
    {
        return (int)$actualAmount * (config('payments.tickets_commission_percentage') / 100);
    }

    /**
     * @param $netAmount
     * @param $commission
     * @return int
     */
    private function calculateMerchantAmount($netAmount, $commission)
    {
        return $netAmount - $commission;
    }

    /**
     * @param $payment
     * @param $amount
     */
    private function setNetPaymentAmount($payment, $amount)
    {
        $payment->net_amount = $amount;
        $payment->update();
    }

    /**
     * @param $payment
     * @param $amount
     */
    private function setCommissionPaymentAmount($payment, $amount)
    {
        $payment->commission_amount = $amount;
        $payment->update();
    }

    /**
     * @param $payment
     * @param $amount
     */
    private function setMerchantPaymentAmount($payment, $amount)
    {
        $payment->merchant_amount = $amount;
        $payment->update();
    }

    /**
     * @param $transaction
     * @param $operator
     * @return mixed
     */
    private function createMerchantPaymentTransaction($transaction, $operator)
    {

        $paymentAccount = MerchantPaymentAccount::where(['merchant_id' => $transaction->merchant_id, 'payment_mode' => $operator])->first();

        return MerchantPayment::create([
            'payment_ref' => strtoupper(PaymentManager::random_code(10)),
            'payment_mode' => $operator,
            'payment_account_id' => $paymentAccount->id,
        ]);
    }

    /**
     * @param $merchantPayments
     * @param $date
     */
    private function updateBookingPayments($merchantPayments, $date): void
    {
        foreach ($merchantPayments as $merchantPayment) {
            $bookingPayments = $this->bookingPaymentsByAccountId($merchantPayment->payment_account_id, $date);

            //print 'INFO: Booking payments'.count($bookingPayments).PHP_EOL;

            foreach ($bookingPayments as $bookingPayment) {
                $bookingPayment->merchant_payment_id = $merchantPayment->id;
                $bookingPayment->update();
                /* print 'INFO: Updated'.json_encode($bookingPayment).PHP_EOL;
                 print '**************************************************'.PHP_EOL;*/
            }
        }
    }

    /**
     * @param $merchantPayment
     */
    public function onMerchantPaymentSuccess($merchantPayment)
    {
        $merchantPayment->payment_stage = 'TRANSFER_SUCCESS';
        $merchantPayment->transaction_status = MerchantPayment::TRANS_STATUS_SETTLED;
        $merchantPayment->transfer_status = true;
        $merchantPayment->update();
    }

    /**
     * @param $merchantPayment
     */
    public function onMerchantPaymentFailure($merchantPayment)
    {
        $merchantPayment->payment_stage = 'TRANSFER_FAIL';
        $merchantPayment->transaction_status = MerchantPayment::TRANS_STATUS_FAILED;
        $merchantPayment->transfer_status = false;
        $merchantPayment->update();
    }

    /**
     * @param $merchantPayment
     */
    public function onMerchantPaymentInitiated($merchantPayment)
    {
        $merchantPayment->payment_stage = 'TRANSFER_INITIATED';
        $merchantPayment->transfer_status = false;
        $merchantPayment->update();
    }

    /**
     * @param $model
     * @param $merchantId
     */
    public function setMerchantPaymentId($model, $merchantId){
        $model->merchant_payment_id =$merchantId;
        $model->update();
    }

    /**
     * @param $payment
     * @return array
     */
    protected function payMerchant(MerchantPayment $payment): array
    {
        $report = array('status' => false);

        if (($payment->transfer_status) || ($payment->payment_stage == 'TRANSFER_INITIATED') || ($payment->payment_stage == 'TRANSFER_SUCCESS')) {
            return array('status' => false, 'error' => 'Payments has already being issued: Ref=' . $payment->payment_ref);
        }

        if (($payment->payment_stage == 'TRANSFER_FAIL') || ($payment->payment_stage == 'PROCESSING_INITIATED')) {

            if ($payment->payment_mode == 'mpesa') {
                //Issue Mpesa payment to number
                $report = $this->issueMpesaPayments($payment);
            } else
                if ($payment->payment_mode == 'tigopesa') {
                    //Issue Tigopesa payment to number
                    $res =
                    $report = $this->issueTigoPesaPayment($payment);
                }
        } else {
            $report = ['status' => false, 'error' => 'Transfer fail or payment has already being initiated for B2C transaction'];
        }
        return $report;
    }
}