<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 12:41 PM
 */

namespace App\Services\Bookings;


use App\Jobs\ConfirmBookingPayment;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\ScheduleSeat;
use App\Models\Seat;
use App\Models\TigoOnlineC2B;
use App\Models\Trip;
use App\Services\Payments\PaymentManager;

trait AddBookingService
{
    private $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->paymentManager = $paymentManager;
    }

    public function processNewBooking(array $bookingDetails, $seat, $scheduleId, $tripId){

        try{
            $booking = $this->createNewBooking($bookingDetails, $scheduleId, $tripId, $seat);

            //ConfirmBookingPayment::dispatch($booking, $scheduleSeat)->delay(now()->addMinutes(5));

            return ['status'=>true,'booking'=>$booking];

        }catch (\Exception $exception){
            return ['status'=>false, 'error'=>$exception->getMessage()];
        }
        //$payment = $bookingDetails['payment'];

        /*if (!$isBooked) {
            //Create booking

            list($booking, $scheduleSeat) = $this->createNewBooking($bookingDetails, $scheduleId, $tripId, $seat);

            //Create bookingPayment
            $bookingPayment = BookingPayment::create([
                'payment_ref'=>strtoupper(PaymentManager::random_code(8)),
                'amount'=>$booking->price,
                'booking_id'=>$booking->id,
                'method'=>$payment,
                'phone_number'=>$booking->phonenumber,
            ]);

            if ($payment == 'mpesa') {

                $mpesaC2B = $this->paymentManager->initialiazeMPESAPaymentC2B([
                    'msisdn'=>$bookingDetails['phonenumber'],
                    'amount'=>$booking->price,
                    'account_reference'=>$bookingPayment->payment_ref,
                    'booking_payment_id'=>$bookingPayment->id,
                ]);
                $paymentModel = $mpesaC2B;
                $isInitialized  = isset($mpesaC2B)?true:false;

            } else
                if ($payment == 'tigopesa') {
                    $tigoOnlineC2B = $this->paymentManager->initialiazeTigoSecureC2B(array(
                        TigoOnlineC2B::COLUMN_REFERENCE => $bookingPayment->payment_ref,//strtoupper(PaymentManager::random_code(12)),
                        TigoOnlineC2B::COLUMN_PHONE_NUMBER => $booking->phonenumber,
                        TigoOnlineC2B::COLUMN_FIRST_NAME =>$booking->firstname,
                        TigoOnlineC2B::COLUMN_LAST_NAME => $booking->lastname,
                        TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID => $bookingPayment->id,
                        TigoOnlineC2B::COLUMN_TAX =>'0',
                        TigoOnlineC2B::COLUMN_FEE => '0',
                        TigoOnlineC2B::COLUMN_AMOUNT => $booking->price,
                    ));
                    $paymentModel = $tigoOnlineC2B;
                    $isInitialized  = isset($tigoOnlineC2B)?true:false;

                }
        }*/
    }

    /**
     * @param $seat
     * @param $busId
     * @param $scheduleId
     * @return array
     */
    public function reserveSeat($seat, $busId, $scheduleId){
        $seat = Seat::select(['*'])->where([Seat::COLUMN_BUS_ID => $busId, Seat::COLUMN_SEAT_NAME => $seat])->first();

        if (!isset($seat)) {
            return ['status'=>false,'error'=>'Selected seat id invalid'];
        }

        $isBooked = $this->checkSeatIsBooked($seat->id, $scheduleId);

        if ($isBooked){
            return ['status'=>false, 'error'=>'The seat has already booked'];
        }

        $this->markSeatAsBooked($scheduleId, $seat);

        return ['status'=>true];
    }

    private function checkSeatIsBooked($seatId, $scheduleId){

        $seat = ScheduleSeat::where([
            ScheduleSeat::COLUMN_SEAT_ID=>$seatId,
            ScheduleSeat::COLUMN_SCHEDULE_ID=>$scheduleId,
        ])->first();

        return isset($seat);
    }

    /**
     * @param $scheduleId
     * @param $seat
     * @return mixed
     */
    private function markSeatAsBooked($scheduleId, $seat){
        return ScheduleSeat::create($this->getScheduleSeatParameterArray($scheduleId, $seat));
    }

    /**
     * @param array $bookingDetails
     * @param $scheduleId
     * @param $tripId
     * @param $seat
     * @return mixed
     */
    protected function createNewBooking(array $bookingDetails, $scheduleId, $tripId, $seat)
    {
        $booking = Booking::create($this->getBookingParameterArray($bookingDetails, $scheduleId, $tripId, $seat));

        return $booking;
    }

    /**
     * @param array $bookingDetails
     * @param $scheduleId
     * @param $tripId
     * @param $seat
     * @return array
     */
    protected function getBookingParameterArray(array $bookingDetails, $scheduleId, $tripId, $seat): array
    {
        $trip = Trip::find($tripId);

        return [
            Booking::COLUMN_TITLE => $bookingDetails[Booking::COLUMN_TITLE],
            Booking::COLUMN_FIRST_NAME => $bookingDetails[Booking::COLUMN_FIRST_NAME],
            Booking::COLUMN_LAST_NAME => $bookingDetails[Booking::COLUMN_LAST_NAME],
            Booking::COLUMN_PHONE_NUMBER => $bookingDetails[Booking::COLUMN_PHONE_NUMBER],
            Booking::COLUMN_EMAIL => $bookingDetails[Booking::COLUMN_EMAIL],
            Booking::COLUMN_PAYMENT => $bookingDetails[Booking::COLUMN_PAYMENT],
            Booking::COLUMN_SEAT_ID => $seat->id,
            Booking::COLUMN_PRICE => $trip->price,
            Booking::COLUMN_TRIP_ID => $tripId,
            Booking::COLUMN_STATUS => Booking::STATUS_PENDING,
            Booking::COLUMN_SCHEDULE_ID => $scheduleId,
        ];
    }

    /**
     * @param $scheduleId
     * @param $seat
     * @return array
     */
    protected function getScheduleSeatParameterArray($scheduleId, $seat): array
    {
        return [
            ScheduleSeat::COLUMN_SCHEDULE_ID => $scheduleId,
            ScheduleSeat::COLUMN_SEAT_ID => $seat->id,
            ScheduleSeat::COLUMN_STATUS => ScheduleSeat::STATUES['booked']
        ];
    }
}