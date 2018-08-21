<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/3/2018
 * Time: 4:45 PM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Nathanmac\Utilities\Parser\Parser;
use Nathanmac\Utilities\Responder\Responder;

class ApiTest extends Controller
{

    public function test()
    {
        $pub = <<<publickey
-----BEGIN PUBLIC KEY-----
MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALqbHeRLCyOdykC5SDLqI49ArYGYG1mq
aH9/GnWjGavZM02fos4lc2w6tCchcUBNtJvGqKwhC5JEnx3RYoSX2ucCAwEAAQ==
-----END PUBLIC KEY-----
publickey;
        $string = '1234abcdV';
        $pk  = openssl_get_publickey($pub);
        openssl_public_encrypt($string, $encrypted, $pk);
        echo base64_encode($encrypted);

    }
}