<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 12/8/2018
 * Time: 2:14 AM
 */

namespace App\Services\Tickets;

use App\Models\BookingPayment;
use App\Models\MpesaC2B;
use App\Models\Ticket;
use App\Models\TigoOnlineC2B;

trait TicketVerification
{
    public function verifyTicketByReference($reference){

        $ticket = Ticket::where(['ticket_ref'=>$reference])->first();

        if(isset($ticket)){
            return ['status'=>true,'booking'=>$ticket->booking];
        }

        $bookingPayment = BookingPayment::where(['payment_ref'=>$reference])->first();

        if(isset($bookingPayment)){
            return ['status'=>true,'booking'=>$bookingPayment->booking];
        }

        $mpesaC2B = MpesaC2B::where(['account_reference'=>$reference])->first();

        if(isset($mpesaC2B)){
            return ['status'=>true,'booking'=>$mpesaC2B->bookingPayment->booking];
        }

        $tigoC2B = TigoOnlineC2B::where(['reference'=>$reference])->first();

        if(isset($tigoC2B)){
            return ['status'=>true,'booking'=>$tigoC2B->bookingPayment->booking];
        }

        return ['status'=>false];
    }
}