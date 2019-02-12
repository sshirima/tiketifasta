<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 2/11/2019
 * Time: 6:51 PM
 */

namespace App\Domain\NotifyUsers;


use App\Domain\NotifyUsers\Services\Smpp;
use Illuminate\Support\Facades\Log;

class SendSMSToUser
{

    use NotifyUsersRepository, Smpp;

    protected $operator;
    protected $sender_id;
    protected $system_id;
    protected $password;
    protected $port;
    protected $host;

    public function __construct($operator)
    {
        $this->operator = $operator;
        $this->setSMSCConfigurationParameters($operator);
    }

    public function sendSMSToOneRecipient($recipient, $message){
        Log::info("Sending one SMS: $recipient");
        try{
            $smsModel = $this->saveSentSMSModel([
                'sender' => $this->getSMSSenderId(),
                'receiver' => $recipient,
                'message' => $message,
                'operator' => $this->operator
            ]);

            $this->open($this->host, $this->port,$this->system_id, $this->password);

            $isSent = $this->send_long($this->sender_id,$recipient, $message);

            $this->updateSMSSentStatus($isSent, $smsModel);

            if(!$isSent){
                Log::info("Sending SMS to $recipient failed, operator $this->operator");
                $response = ['status'=>false];
            } else {
                $response = ['status'=>true];
            }

        }catch (\Exception $exception){
            $response = ['status'=>false, 'error'=>$exception->getMessage()];
        }
        return $response;
    }

    private function setSMSCConfigurationParameters($operator){
        $config = $this->getSMSCOperatorConfiguration($operator);
        $this->host = $config['host'];
        $this->system_id = $config['system_id'];
        $this->password = $config['password'];
        $this->port = $config['port'];
        $this->sender_id = $this->getSMSSenderId();
    }
}