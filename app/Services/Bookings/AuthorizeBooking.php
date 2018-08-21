<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Bookings;

use App\Models\Booking;

trait AuthorizeBooking
{


    /**
     * @param Booking $booking
     */
    public function setBookingExpired(Booking $booking){
        $booking->status = 'EXPIRED';
        $booking->update();

    }

    /**
     * @param Booking $booking
     */
    public function setBookingAuthorized(Booking $booking){
        $booking->status = 'AUTHORIZED';
        $booking->update();

    }
}