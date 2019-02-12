<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/11/2019
 * Time: 6:33 PM
 */

namespace App\Domain\NotifyUsers;


class NotifyUsersAggregate
{
    public function sendSMSToOne($recipient, $message, $operator){

        $smsSender = new SendSMSToUser($operator);

        return $smsSender->sendSMSToOneRecipient($recipient, $message);
    }
}