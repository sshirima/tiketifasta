<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/8/2018
 * Time: 4:50 PM
 */

namespace App\Services\SMS;


use App\Models\SentSMS;
use App\Models\Ticket;

trait SendSMS
{
    /**
     * @param $operator
     * @param $phoneNumber
     * @param $message
     * @return array|bool
     */
    public function sendOneSMS($operator, $phoneNumber, $message){

        $numCheck = $this->checkNumber($phoneNumber);

        if ($numCheck['status'] == false){
            \Log::channel('sms_logs')->error($numCheck['error'] . PHP_EOL);
            return ['status'=>false,'error'=>$numCheck['error']];
        }

        $sendSMS = $this->sendMessage($operator, $numCheck['number'], $message);

        if($sendSMS['status']){
            return ['status'=>true];
        } else {
            return ['status'=>false,'error'=>$sendSMS['error']];
        }
    }

    /**
     * @param Ticket $ticket
     * @param $operator
     * @return array|bool
     */
    public function sendTicketReference(Ticket $ticket, $operator){

        $numCheck = $this->checkNumber($ticket->booking->phonenumber);

        if ($numCheck['status'] == false){
            \Log::channel('sms_logs')->error($numCheck['error'] . PHP_EOL);
            return false;
        }

        $phoneNumber = $numCheck['number'];

        $format = config('smsc.format');

        $message = sprintf($format,$ticket->booking->firstname,strtoupper($ticket->ticket_ref));

        $isSent = $this->sendMessage($operator, $phoneNumber, $message);

        if($isSent){
            return $isSent;
        } else {
            return ['status'=>false,'error'=>'Failed to send SMS'];
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

    /**
     * @param $operator
     * @param $phoneNumber
     * @param $message
     * @return mixed
     */
    protected function createSentSMSModel($operator, $phoneNumber, $message)
    {
        $sentSMS = SentSMS::create([
            'sender' => config('smsc.tigo.snmp.settings.sender'),
            'receiver' => $phoneNumber,
            'message' => $message,
            'operator' => $operator
        ]);
        return $sentSMS;
    }

    /**
     * @param $operator
     * @param $phoneNumber
     * @param $message
     * @return array|bool
     */
    protected function sendMessage($operator, $phoneNumber, $message)
    {
        $smpp = new Smpp();

        try{
            if ($operator == 'tigopesa') {

                $sentSMS = $this->createSentSMSModel('TIGO',$phoneNumber, $message);

                $smpp->open(config('smsc.tigo.snmp.account.host'), config('smsc.tigo.snmp.account.port'), config('smsc.tigo.snmp.account.username'), config('smsc.tigo.snmp.account.password'));

                $sendSMSStatus = $smpp->send_long(config('smsc.tigo.snmp.settings.sender'), $phoneNumber, $message);

                //Save SMS
                $sentSMS->is_sent = $sendSMSStatus;
                $sentSMS->update();

                return ['status'=>$sendSMSStatus];
            } else {
                return ['status'=>false,'error'=>'Invalid operator'];
            }
        }catch (\Exception $ex){
            \Log::channel('sms_logs')->error($ex->getMessage() . PHP_EOL);
            return ['status'=>false, 'error'=>$ex->getMessage()];
        }
    }
}