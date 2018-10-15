<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/8/2018
 * Time: 4:50 PM
 */

namespace App\Services\SMS;


trait SendSMS
{
    public function sendToOne($sender,$phoneNumber, $message){
        try{
            $smpp = new Smpp();

            $smpp->setDebug(0);

            $smpp->open(env('TIGO_SMPP_HOST'), env('TIGO_SMPP_PORT'), env('TIGO_SMPP_USERNAME'), env('TIGO_SMPP_PASSWORD'));

            $res = $smpp->send_long($sender,$phoneNumber, $message);

            $smpp->close();

            return $res;

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            return $message;
        }
    }

}