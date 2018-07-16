<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 2:09 PM
 */

namespace App\Services\Payments;


trait MpesaPaymentService
{
    protected $paymentReference;

    public function initialiazeMPESAPayment(array $attributes){
        return true;
    }


}