<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/8/2018
 * Time: 4:50 PM
 */

namespace App\Services\SMS;


use App\Models\Ticket;

trait SendSMS
{
    public function sendTicketReference(Ticket $ticket){
        $phoneNumber = $ticket->booking->phonenumber;

        $format = config('smsc.format');

        $message = sprintf($format,$ticket->booking->firstname,strtoupper($ticket->ticket_ref));

        $smpp = new Smpp();

        if($ticket->booking->payment == 'tigopesa'){
            $smpp->open(config('smsc.tigo.snmp.account.host'), config('smsc.tigo.snmp.account.port'), config('smsc.tigo.snmp.account.username'), config('smsc.tigo.snmp.account.password'));

            return $smpp->send_long(config('smsc.tigo.snmp.settings.sender'),$phoneNumber, $message);
        }else{
           return false;
        }
    }
}