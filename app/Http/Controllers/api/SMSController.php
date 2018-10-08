<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/3/2018
 * Time: 11:09 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Services\SMS\Smpp;
use LaravelSmpp\SmppServiceInterface;

class SmsController extends Controller
{

    public function send()
    {
        // One number
        $sender = 'TIKETIFASTA';//$_GET['senderAddr'];
        $phonenumber = '0714682070';//$_GET['msisdn'];
        $phonemessage = 'Testing message';

        $smpp = new Smpp();
        $smpp->setDebug(0);


        $smpp->open("41.222.182.51", 10501, "TKJINT", "TKJIN@32");


        $smpp->send_long($sender,$phonenumber, $phonemessage);


        $smpp->close();

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }
}