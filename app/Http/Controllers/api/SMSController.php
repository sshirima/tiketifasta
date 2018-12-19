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
            $phoneNumber = $input['phonenumber'];

            if (!is_numeric($phoneNumber)){
                return array('status'=>false,'error'=>'Not numeric number');
            }

            if ((strlen($phoneNumber) == 10)){
                if($this->startsWithZero($phoneNumber,'0')){
                    $phoneNumber = '255'.substr($phoneNumber,1,10);
                    //return array('status'=>true,'number'=> $phoneNumber);
                } else {
                    return array('status'=>false,'error'=>'Number does not start with zero');
                }
            } else if((strlen($phoneNumber) == 12)) {
                //return array('status'=>true,'number'=>$phoneNumber);
            } else {
                return array('status'=>false,'error'=>'10 or 12 digits number required');
            }

            $smpp = new Smpp();
            $smpp->setDebug(1);

            $message = 'Test message';

            $smpp->open('41.223.4.174', '6691', 'tfinance', 'td52fina');

            //echo json_encode($connection);

            $res = $smpp->send_long(config('smsc.tigo.snmp.settings.sender'),$phoneNumber, $message);

            if ($res == true){
                print 'Message sent';
            } else {
                print 'Sending message failed';
            }
            $smpp->close();

            return 'Sent';

        }catch (\Exception $exception){
            $message = $exception->getMessage();
            return $message;
        }

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }

    private function startsWithZero ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}