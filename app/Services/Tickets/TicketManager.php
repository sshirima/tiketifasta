<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/30/2018
 * Time: 2:15 PM
 */

namespace App\Services\Tickets;


use App\Models\BookingPayment;
use App\Models\Ticket;
use App\Models\TigoOnlineC2B;

trait TicketManager
{

    /**
     * @param BookingPayment $bookingPayment
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function createTicket(BookingPayment $bookingPayment){

        $booking = $bookingPayment->booking()->first();

        $ticket = $bookingPayment->ticket()->first();

        if (isset($ticket)){
            return $ticket;
        } else {
            return Ticket::create([
                Ticket::COLUMN_TICKET_REFERENCE => strtoupper($this->generateTicketRef()),
                Ticket::COLUMN_BOOKING_ID => $booking->id,
                Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
                Ticket::COLUMN_PRICE => $bookingPayment->price,
                Ticket::COLUMN_PRICE => $bookingPayment->amount,
                Ticket::COLUMN_STATUS => Ticket::STATUS_VALID,
            ]);
        }
    }

    /**
     * @param Ticket $ticket
     * @return bool
     */
    public function confirmTicket(Ticket $ticket){
        $ticket->status = Ticket::STATUS_CONFIRMED;
        return $ticket->update();
    }


    public function generateTicketMessage(Ticket $ticket, TigoOnlineC2B $tigoOnlineC2B){

        $format = config('smsc.format');

        $customerName = $ticket->booking->firstname;
        $busRegNumber = $tigoOnlineC2B->bookingPayment->booking->trip->bus->reg_number;
        $date = $tigoOnlineC2B->bookingPayment->schedule->day->date;
        $time = $tigoOnlineC2B->bookingPayment->booking->trip->depart_time;
        $from = $tigoOnlineC2B->bookingPayment->booking->trip->from->name;
        $to = $tigoOnlineC2B->bookingPayment->booking->trip->to->name;
        $ticketRef = $ticket->ticket_ref;

        $message = sprintf($format,$customerName, $busRegNumber, $date.' '.$time, $from, $to, strtoupper($ticketRef));

        return $message;
    }
    /**
     * @return bool|string
     */
    private function generateTicketRef(){
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5);
    }
}