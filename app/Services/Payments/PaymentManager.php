<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 2:05 PM
 */

namespace App\Services\Payments;


class PaymentManager
{
    use MpesaPaymentService, TigopesaPaymentService, BookingPaymentService;
}