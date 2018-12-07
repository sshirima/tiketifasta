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
use App\Services\SMS\SendSMS;

trait TicketManager
{

    use SendSMS;

    public function processTicket($transaction){

        $bookingPayment = $transaction->bookingPayment;

        $ticket = $this->createTicket($bookingPayment);

        //$this->confirmTicket($ticket);

        return $ticket;
    }

    /**
     * @param BookingPayment $bookingPayment
     * @return mixed
     */
    public function createTicket(BookingPayment $bookingPayment){

        $booking = $bookingPayment->booking()->first();

        if($bookingPayment->method == 'tigopesa'){
            return Ticket::updateOrCreate([
                Ticket::COLUMN_BOOKING_ID => $booking->id,
                Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
            ],[
                Ticket::COLUMN_TICKET_REFERENCE => strtoupper($this->generateTicketRef(6)),
                Ticket::COLUMN_PRICE => $bookingPayment->amount,
                Ticket::COLUMN_STATUS => Ticket::STATUS_CONFIRMED,
            ]);
        }

        if($bookingPayment->method == 'mpesa'){
            return Ticket::updateOrCreate([
                Ticket::COLUMN_BOOKING_ID => $booking->id,
                Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
            ],[
                Ticket::COLUMN_TICKET_REFERENCE => strtoupper($this->generateTicketRef(6)),
                Ticket::COLUMN_PRICE => $bookingPayment->amount,
                Ticket::COLUMN_STATUS => Ticket::STATUS_VALID,
            ]);
        }
    }


    /**
     * @param Ticket $ticket
     * @param $transaction
     * @return bool
     */
    public function confirmTicket(Ticket $ticket, $transaction){
        $this->sendConfirmationMessageToCustomer($ticket, $transaction);
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