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

    public function bookTicket(array $bookingDetails, $busId, $scheduleId, $tripId){
        $isBooked = false;
        $isInitialized = false;
        $booking = null;

        //Check payment method to be used
        $seat = Seat::select(['*'])->where([Seat::COLUMN_BUS_ID => $busId, Seat::COLUMN_SEAT_NAME => $bookingDetails['seat']])->first();

        if (isset($seat)) {
            $isBooked = $this->seatIsBooked($seat->id, $scheduleId);
        }

        //Initiate payment gateway if the seat is not booked
        $paymentReference = null;
        $scheduleSeat = null;
        $paymentModel = null;

        $payment = $bookingDetails['payment'];
        if (!$isBooked) {
            //Create booking

            list($booking, $scheduleSeat) = $this->storeBookingInfo($bookingDetails, $scheduleId, $tripId, $seat);

            //Create bookingPayment
            $bookingPayment = BookingPayment::create([
                'payment_ref'=>strtoupper(PaymentManager::random_code(12)),
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
                        TigoOnlineC2B::COLUMN_REFERENCE => strtoupper(PaymentManager::random_code(12)),
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
        }

        //Save the booking records
        if ($isInitialized) {
            //Initialize payment timer
            //ConfirmBookingPayment::dispatch($booking, $scheduleSeat)->delay(now()->addMinutes(5));
        }

        return ['booking'=>$booking,'paymentModel'=>$paymentModel];
    }

    public function saveBooking(array $attributes){
        return Booking::create($attributes);
    }

    public function createScheduleSeat(array $attributes){
        return ScheduleSeat::create($attributes);
    }

    public function seatIsBooked($seatId, $scheduleId){

        $seat = ScheduleSeat::where([
            ScheduleSeat::COLUMN_SEAT_ID=>$seatId,
            ScheduleSeat::COLUMN_SCHEDULE_ID=>$scheduleId,
        ])->first();

        return isset($seat);
    }

    /**
     * @param array $bookingDetails
     * @param $scheduleId
     * @param $tripId
     * @param $seat
     * @param $paymentReference
     * @return array
     */
    protected function storeBookingInfo(array $bookingDetails, $scheduleId, $tripId, $seat): array
    {
        $trip = Trip::find($tripId);

        $booking = $this->saveBooking([
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
        ]);

        //Mark the seat as booked
        $scheduleSeat = $this->createScheduleSeat([
            ScheduleSeat::COLUMN_SCHEDULE_ID => $scheduleId,
            ScheduleSeat::COLUMN_SEAT_ID => $seat->id,
            ScheduleSeat::COLUMN_STATUS => ScheduleSeat::STATUES['booked']
        ]);
        return array($booking, $scheduleSeat);
    }
}