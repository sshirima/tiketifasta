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
use Illuminate\Http\Request;
use LaravelSmpp\SmppServiceInterface;

class SmsController extends Controller
{

    public function send(Request $request, SmppServiceInterface $smpp)
    {
        // One number
        try{
            $input = $request->all();
            $sender = $input['sender'];
            $phoneNumber = $input['phonenumber'];
            $message = $input['message'];

            $smpp->sendOne($phoneNumber, $message);
            /*$smpp = new Smpp();
            $smpp->setDebug(0);

            $connection = $smpp->open("41.222.182.51", 10501, "TKJINT", "TKJIN@32");

            echo json_encode($connection);

            $res = $smpp->send_long($sender,$phoneNumber, $message);


            $smpp->close();*/

            return $res;

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            return $message;
        }

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }
}