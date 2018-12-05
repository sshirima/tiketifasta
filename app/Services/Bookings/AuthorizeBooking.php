<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Bookings;

use App\Models\Booking;
use App\Models\ScheduleSeat;

trait AuthorizeBooking
{
    /**
     * @param $booking
     */
    public function deleteFailedBooking($booking){

        /*$bookingPayment = $booking->bookingPayment;
        if(isset($bookingPayment)){
            $bookingPayment->delete();
        }*/
        ScheduleSeat::where(['seat_id'=>$booking->seat_id,'schedule_id'=>$booking->schedule_id])->delete();

        $booking->delete();
    }

    /**
     * @param $transaction
     */
    public function deleteBookingByTransaction($transaction){

        $booking = $transaction->bookingPayment->booking;

        ScheduleSeat::where(['seat_id'=>$booking->seat_id,'schedule_id'=>$booking->schedule_id])->delete();

        $booking->delete();
    }

    public function setBookingCancelled(Booking $booking){
        $this->updateBookingStatus($booking, Booking::STATUS_CANCELLED);
    }

    /**
     * @param Booking $booking
     */
    public function setBookingExpired(Booking $booking){
        $this->updateBookingStatus($booking, Booking::STATUS_EXPIRED);

    }

    /**
     * @param Booking $booking
     */
    public function setBookingConfirmed(Booking $booking){
        $this->updateBookingStatus($booking, Booking::STATUS_CONFIRMED);
    }

    /**
     * @param Booking $booking
     */
    public function setBookingPending(Booking $booking){
        $this->updateBookingStatus($booking, Booking::STATUS_PENDING);
    }

    /**
     * @param Booking $booking
     * @param $status
     */
    private function updateBookingStatus(Booking $booking, $status){
        $booking->status = $status;
        $booking->update();
    }
}