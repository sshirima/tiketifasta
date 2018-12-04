<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/4/2018
 * Time: 10:43 AM
 */

namespace App\Services\Payments\BookingPayments;


use App\Models\Booking;
use App\Models\BookingPayment;
use App\Services\Payments\PaymentManager;
use App\Services\Payments\Tigosecure\TigoTransactionC2B;

trait BookingPaymentProcessor
{

    use TigoTransactionC2B;

    public function processNewBookingPayment(Booking $booking){

        $bookingPayment = BookingPayment::create($this->getBookingParamArray($booking));

        if ($bookingPayment->method == 'mpesa'){
            return ['status'=>true,'bookingPayment'=>$bookingPayment];
        }

        if ($bookingPayment->method == 'tigopesa'){
            $res = $this->authorizeTigoC2BTransaction($bookingPayment);
            
            if(!$res['status']){
                return ['status'=>false,'bookingPayment'=>$bookingPayment, 'error'=>$res['error']];
            }

            if(array_key_exists('error', $res)){
                return ['status'=>false,'bookingPayment'=>$bookingPayment, 'error'=>$res['error']];
            }
            return ['status'=>true,'bookingPayment'=>$bookingPayment,'redirectUrl'=>$res['redirectUrl']];
        }

        return ['status'=>false,'error'=>'Invalid operation'];
    }

    /**
     * @param Booking $booking
     * @return array
     */
    protected function getBookingParamArray(Booking $booking): array
    {
        return [
            'payment_ref' => strtoupper(PaymentManager::random_code(8)),
            'amount' => $booking->price,
            'booking_id' => $booking->id,
            'method' => $booking->payment,
            'phone_number' => $booking->phonenumber,
        ];
    }
}