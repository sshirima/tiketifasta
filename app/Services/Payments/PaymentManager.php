<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 2:05 PM
 */

namespace App\Services\Payments;


use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Tigosecure\TigoOnline;
use App\Services\Payments\Tigosecure\TigoTransactionC2B;

class PaymentManager
{
    use TigoTransactionC2B;

    private $mpesa;
    private $tigoOnline;

    public function __construct(Mpesa $mpesa, TigoOnline $tigoOnline)
    {
        $this->mpesa = $mpesa;
        $this->tigoOnline = $tigoOnline;
    }

    public function initialiazeMPESAPaymentC2B(array $attributes){

        $mpesaC2B = $this->mpesa->initializePaymentC2B($attributes);

        return $mpesaC2B;
    }

    public function mpesaPaymentReferenceC2B(array $attributes){

        $mpesaC2B = $this->mpesa->initializePaymentC2B($attributes);

        return $mpesaC2B;
    }

    public function initialiazeTigoSecureC2B(array $attributes){
        return $this->tigoOnline->initializePaymentC2B($attributes);
    }

    public static function random_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public static function getCurrentTimestamp(){
        date_default_timezone_set('Africa/Dar_es_Salaam');
        return date('YmdHis');
    }
}