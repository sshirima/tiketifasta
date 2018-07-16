<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/10/2018
 * Time: 2:08 PM
 */

namespace App\Services\Payments;


use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Ticket;

trait BookingPaymentService
{
    /**
     * @param array $attributes
     * @return null
     */
    public function addBookingPayment(array $attributes){
        $bookingPayment = null;
        //Check the booking to be paid
        $booking = Booking::where([Booking::COLUMN_PAYMENT_REF =>$attributes['payment_ref']])->first();
        if (isset($booking)){
            //Check if the same reference has been already paid
            $bookingPayment = BookingPayment::where([BookingPayment::COLUMN_PAYMENT_REF =>$attributes['payment_ref']])->first();
            if (!isset($bookingPayment)){

                $attributes[BookingPayment::COLUMN_BOOKING_ID] = $booking->id;

                $bookingPayment = BookingPayment::create($attributes);

                //Generate and create ticket
                $ticket = Ticket::create([
                    Ticket::COLUMN_TICKET_REFERENCE => 'TFKIL20180623',
                    Ticket::COLUMN_BOOKING_ID => $booking->id,
                    Ticket::COLUMN_PRICE => $booking->price,
                    Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
                    Ticket::COLUMN_STATUS => Ticket::STATUS_VALID,
                ]);

                if (isset($ticket)){
                    //Mark booking as paid
                    $booking->status = Booking::$STATUS_CONFIRMED;
                    $booking->update();
                }

                //Send SMS to the customer

            }
        }
        return $bookingPayment;
    }

}