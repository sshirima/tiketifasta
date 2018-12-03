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

trait TicketManager
{

    public function processTicket($transaction){

        $bookingPayment = $transaction->bookingPayment;

        $ticket = $this->createTicket($bookingPayment);

        //$this->confirmTicket($ticket);

        $this->sendConfirmationMessageToCustomer($ticket, $transaction);

        return $ticket;
    }

    /**
     * @param BookingPayment $bookingPayment
     * @return mixed
     */
    public function createTicket(BookingPayment $bookingPayment){

        $booking = $bookingPayment->booking()->first();

        return Ticket::createOrUpdate([
            Ticket::COLUMN_BOOKING_ID => $booking->id,
            Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
        ],[
            Ticket::COLUMN_TICKET_REFERENCE => strtoupper($this->generateTicketRef(6)),
            Ticket::COLUMN_PRICE => $bookingPayment->amount,
            Ticket::COLUMN_STATUS => Ticket::STATUS_CONFIRMED,
        ]);
    }



    /**
     * @param Ticket $ticket
     * @return bool
     */
    public function confirmTicket(Ticket $ticket){
        $ticket->status = Ticket::STATUS_CONFIRMED;
        return $ticket->update();
    }

    /**
     * @param int $limit
     * @return bool|string
     */
    private function generateTicketRef($limit=6){
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}