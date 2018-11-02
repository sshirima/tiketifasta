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
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use LaravelSmpp\SmppService;

class SmsController extends Controller
{

    public function send(Request $request)
    {
        // One number
        try{
            $input = $request->all();
            $sender = $input['sender'];
            $phoneNumber = $input['phonenumber'];
            $message = $input['message'];

            $smpp = new Smpp();
            $smpp->setDebug(0);

            $format = config('smsc.format');

            $message = sprintf($format,'Samson','TKB4G667');

            $smpp->open(config('smsc.tigo.snmp.account.host'), config('smsc.tigo.snmp.account.port'), config('smsc.tigo.snmp.account.username'), config('smsc.tigo.snmp.account.password'));

            //echo json_encode($connection);

            $res = $smpp->send_long($sender,$phoneNumber, $message);

            if ($res == true){
                print 'Message sent';
            } else {
                print 'Sending message failed';
            }
            //$smpp->close();

            //return 'Sent';

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            return $message;
        }

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }
}