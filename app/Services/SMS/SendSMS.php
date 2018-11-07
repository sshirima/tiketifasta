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
    public function sendTicketReference(Ticket $ticket, $operator){

        $numCheck = $this->checkNumber($ticket->booking->phonenumber);

        if ($numCheck['status'] == false){
            \Log::channel('sms_logs')->error($numCheck['error'] . PHP_EOL);
            return false;
        }

        $phoneNumber = $numCheck['number'];

        $format = config('smsc.format');

        $message = sprintf($format,$ticket->booking->firstname,strtoupper($ticket->ticket_ref));

        $smpp = new Smpp();

        if($operator == 'tigopesa'){
            $smpp->open(config('smsc.tigo.snmp.account.host'), config('smsc.tigo.snmp.account.port'), config('smsc.tigo.snmp.account.username'), config('smsc.tigo.snmp.account.password'));

            return $smpp->send_long(config('smsc.tigo.snmp.settings.sender'),$phoneNumber, $message);
        }else{
           return false;
        }
    }

    private function checkNumber($phoneNumber){

        if (!is_numeric($phoneNumber)){
            return array('status'=>false,'error'=>'Not numeric number');
        }

        if ((strlen($phoneNumber) == 10)){
            if($this->startsWithZero($phoneNumber,'0')){
                $phoneNumber = '255'.substr($phoneNumber,1,10);
                return array('status'=>true,'number'=> $phoneNumber);
            } else {
                return array('status'=>false,'error'=>'Number does not start with zero');
            }
        } else if((strlen($phoneNumber) == 12)) {
            return array('status'=>true,'number'=>$phoneNumber);
        } else{
            return array('status'=>false,'error'=>'10 or 12 digits number required');
        }
    }

    private function startsWithZero ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}