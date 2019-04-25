<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/11/2019
 * Time: 6:33 PM
 */

namespace App\Domain\NotifyUsers;


use App\Domain\NotifyUsers\Job\NotifyByEmailJob;
use App\Domain\NotifyUsers\Mail\AvailabilityStatusChangeMail;

class NotifyUsersAggregate
{
    public function sendSMSToOne($recipient, $message, $operator){

        $smsSender = new SendSMSToUser($operator);

        return $smsSender->sendSMSToOneRecipient($recipient, $message);
    }

    public function sendEmailOnAvailabilityStatusChange($emailAddress, $status){
        $mail = new AvailabilityStatusChangeMail('emails.availability_status_change',['availability_status'=>$status]);
        NotifyByEmailJob::dispatch($emailAddress, $mail);
    }
}