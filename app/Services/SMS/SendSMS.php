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
use App\Models\TigoOnlineC2B;

trait SendSMS
{

    /**
     * @param $ticket
     * @param $transaction
     * @return array|bool
     */
    public function sendConfirmationMessageToCustomer($ticket, $transaction){

        $message = $this->generateTicketMessage($ticket, $transaction);

        $operator = $ticket->booking->payment;
        $phoneNumber = $ticket->booking->phonenumber;

        $numCheck = $this->checkNumber($phoneNumber);

        if ($numCheck['status'] == false){
            \Log::error('SMS receiver number invalid, message:'.$numCheck['error'] . PHP_EOL);
            return false;
        }

        $isSent = $this->sendMessage($operator, $numCheck['number'], $message);

        if($isSent){
            return $isSent;
        } else {
            return ['status'=>false,'error'=>$isSent['error']];
        }
    }

    /**
     * @param Ticket $ticket
     * @param $bookingPayment
     * @return string
     */
    public function generateTicketMessage(Ticket $ticket, $bookingPayment){

        $format = config('smsc.format');

        $customerName = $ticket->booking->firstname;
        $busRegNumber = $bookingPayment->booking->trip->bus->reg_number;

        $date = $bookingPayment->booking->schedule->day->date;
        $time = $bookingPayment->booking->trip->depart_time;
        $formattedDate = date('Y:m:d G:i', strtotime($date.' '.$time));
        //$formattedDate = \DateTime::createFromFormat('Y-m-d G:i', $date.' '.$time)->format('Y:m:d G:i');

        $from = $bookingPayment->booking->trip->from->name;
        $to = $bookingPayment->booking->trip->to->name;
        $ticketRef = $ticket->ticket_ref;

        $message = sprintf($format,$customerName, $busRegNumber,  $from, $to, $formattedDate, strtoupper($ticketRef));

        return $message;
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

                $smpp->open(config('smsc.tigo_smpp_host'), config('smsc.tigo_smpp_port'), config('smsc.tigo_smpp_system_id'), config('smsc.tigo_smpp_password'));

                $sendSMSStatus = $smpp->send_long(config('smsc.smpp_sender_id'), $phoneNumber, $message);

                //Save SMS
                $sentSMS->is_sent = $sendSMSStatus;
                $sentSMS->update();

                return ['status'=>$sendSMSStatus];
            } if ($operator == 'mpesa'){
                $sentSMS = $this->createSentSMSModel('VODACOM',$phoneNumber, $message);

                $smpp->open(config('smsc.voda_smpp_host'), config('smsc.voda_smpp_port'), config('smsc.voda_smpp_system_id'), config('smsc.voda_smpp_password'));

                $sendSMSStatus = $smpp->send_long(config('smsc.smpp_sender_id'), $phoneNumber, $message);

                //Save SMS
                $sentSMS->is_sent = $sendSMSStatus;
                $sentSMS->update();

                return ['status'=>$sendSMSStatus];
            }
            else {
                return ['status'=>false,'error'=>'Invalid operator'];
            }
        }catch (\Exception $ex){
            \Log::error('Sending message, failed, error:'.$ex->getMessage() . PHP_EOL);
            return ['status'=>false, 'error'=>$ex->getMessage()];
        }
    }
}