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
use App\Models\ScheduleSeat;
use App\Models\Seat;
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

        //Check payment method to be used
        $seat = Seat::select(['*'])->where([Seat::COLUMN_BUS_ID => $busId, Seat::COLUMN_SEAT_NAME => $bookingDetails['seat']])->first();
        if (isset($seat)) {
            $isBooked = $this->seatIsBooked($seat->id, $scheduleId);
        }

        //Initiate payment gateway if the seat is not booked
        $paymentReference = null;
        $payment = $bookingDetails['payment'];
        if (!$isBooked) {
            if ($payment == 'mpesa') {
                $isInitialized = $this->paymentManager->initialiazeMPESAPayment(array());
            } else
                if ($payment == 'tigopesa') {
                    $isInitialized = $this->paymentManager->initializeTIGOPESAPayment(array());
                }
        }

        if ($isInitialized) {
            $paymentReference = 'QUUR1245579';
        }

        $booking = null;

        //Save the booking records
        if ($isInitialized && isset($paymentReference)) {

            list($booking, $scheduleSeat) = $this->storeBookingInfo($bookingDetails, $scheduleId, $tripId, $seat, $paymentReference);

            //Initialize payment timer
            ConfirmBookingPayment::dispatch($booking, $scheduleSeat)->delay(now()->addMinutes(1));
        }

        return $booking;
    }

    public function saveBooking(array $attributes){
        return Booking::create($attributes);
    }

    public function markSeatAsBooked(array $attributes){
        return ScheduleSeat::create($attributes);
    }

    public function seatIsBooked($seatId, $scheduleId){
        $seat = ScheduleSeat::where([
            ScheduleSeat::COLUMN_SEAT_ID=>$seatId,
            ScheduleSeat::COLUMN_SCHEDULE_ID=>$scheduleId,
        ])->first();

        if (isset($seat)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param array $bookingDetails
     * @param $scheduleId
     * @param $tripId
     * @param $seat
     * @param $paymentReference
     * @return array
     */
    protected function storeBookingInfo(array $bookingDetails, $scheduleId, $tripId, $seat, $paymentReference): array
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
            Booking::COLUMN_SCHEDULE_ID => $scheduleId,
            Booking::COLUMN_PAYMENT_REF => $paymentReference,
        ]);

        //Mark the seat as booked
        $scheduleSeat = $this->markSeatAsBooked([
            ScheduleSeat::COLUMN_SCHEDULE_ID => $scheduleId,
            ScheduleSeat::COLUMN_SEAT_ID => $seat->id,
            ScheduleSeat::COLUMN_STATUS => ScheduleSeat::STATUES['booked']
        ]);
        return array($booking, $scheduleSeat);
    }
}