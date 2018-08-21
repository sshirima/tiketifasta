<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 2:05 PM
 */

namespace App\Services\Payments;


use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Tigosecure\TigoOnlinePaymentC2B;

class PaymentManager
{
    use TigoUSSDPaymentService, BookingPaymentService, TigoOnlinePaymentC2B;

    private $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    public function initialiazeMPESAPaymentC2B(array $attributes){

        $mpesaC2B = $this->mpesa->initializePaymentC2B($attributes);

        return $mpesaC2B;
    }

    public function mpesaPaymentReferenceC2B(){
        return $this->mpesa->getPaymentReference();
    }

    public static function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}