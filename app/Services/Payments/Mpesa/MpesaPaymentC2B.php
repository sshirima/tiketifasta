<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:27 PM
 */

namespace App\Services\Payments\Mpesa;


use App\Models\MpesaC2B;
use App\Services\Payments\Mpesa\xml\MpesaC2BData;
use App\Services\Payments\PaymentManager;

trait MpesaPaymentC2B
{
    use MpesaC2BData;

    public function initializePaymentC2B(array $attributes)
    {
        //Stage 3 create transaction reference, msisdn, amount, reference
        return MpesaC2B::create($attributes);

    }

    public function validatePaymentC2B(array $attributes)
    {
        //Stage 2, fetch by account reference]

        $mpesaC2B = MpesaC2B::where(['account_reference'=>$attributes['account_reference']])->first();

        if (!isset($mpesaC2B)) {
            return array('status'=>false,'message'=>'Transaction not found');
        }

        if ($this->isDuplicateC2B($attributes['mpesa_receipt'])) {
            return array('status'=>false,'message'=>'Transaction is duplicated');
        }

        if ($mpesaC2B->amount != $attributes['amount']) {
            return array('status'=>false,'message'=>'Paid amount is not equal to ticket price');
        }

        $mpesaC2B->command_id = $attributes['command_id'];
        $mpesaC2B->initiator = $attributes['initiator'];
        $mpesaC2B->og_conversation_id = $attributes['og_conversation_id'];
        $mpesaC2B->recipient = $attributes['recipient'];
        $mpesaC2B->mpesa_receipt = $attributes['mpesa_receipt'];
        $mpesaC2B->transaction_date = $attributes['transaction_date'];
        $mpesaC2B->transaction_id = $attributes['transaction_id'];
        $mpesaC2B->conversation_id = $attributes['conversation_id'];
        $mpesaC2B->authorized_at = date('Y-m-d H:i:s');
        $mpesaC2B->stage = '2';

        return array('status'=>$mpesaC2B->update(),'mpesaC2B'=>$mpesaC2B);
    }

    public function processPaymentC2B(MpesaC2B $mpesaC2B)
    {
        //Generate service number
        $mpesaC2B->service_receipt = strtoupper(PaymentManager::random_code(8));
        $mpesaC2B->stage = '1';

        return $mpesaC2B->update();
    }

    public function confirmPaymentC2B(MpesaC2B $mpesaC2B)
    {
        //Stage 0
        $mpesaC2B->stage ='0';
        $mpesaC2B->service_status = 'confirmed';
        $mpesaC2B->update();
    }

    public function cancelPaymentC2B(MpesaC2B $mpesaC2B)
    {
        //Stage 0
        $mpesaC2B->service_status = 'cancelled';
        $mpesaC2B->update();
    }

    public function isDuplicateC2B($mpesaId)
    {
        $mpesaC2B = MpesaC2B::where(['mpesa_receipt' => $mpesaId])->first();

        if (isset($mpesaC2B)) {
            return true;
        } else {
            return false;
        }
    }

    function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}