<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 10/3/2018
 * Time: 11:09 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use LaravelSmpp\SmppServiceInterface;

class SmsController extends Controller
{
    public function send(SmppServiceInterface $smpp)
    {
        // One number
        $smpp->sendOne(255754710618, 'Hi, this SMS was send via SMPP protocol');

        // Multiple numbers
        //$smpp->sendBulk([1234567890, 0987654321], 'Hi!');
    }
}