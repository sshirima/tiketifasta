<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/11/2019
 * Time: 6:34 PM
 */

namespace App\Domain\NotifyUsers;


use App\Models\SentSMS;

trait NotifyUsersRepository
{
    protected function getSMSSenderId(){
        return config('notify_users.sms.sender_id');
    }

    protected function getSMSCOperatorConfiguration($operator){
        return config('notify_users.sms.operators')[$operator];
    }

    protected function saveSentSMSModel($attributes)
    {
        $sentSMS = SentSMS::create($attributes);
        return $sentSMS;
    }

    /**
     * @param $isSent
     * @param $sms
     */
    private function updateSMSSentStatus($isSent, $sms): void
    {
        $sms->is_sent = $isSent;
        $sms->update();
    }
}