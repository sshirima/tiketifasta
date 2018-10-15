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
            /*$smppService = new SmppService(new Repository(config('laravel-smpp')));
            $smppService->sendOne($phoneNumber, $message);*/

            $smpp = new Smpp();
            $smpp->setDebug(1);

            $connection = $smpp->open("41.222.182.51", 10501, "TKJINT", "TKJIN@32");

            echo json_encode($connection);

            $res = $smpp->send_long($sender,$phoneNumber, $message);


            $smpp->close();

            return 'Sent';

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            return $message;
        }

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }
}