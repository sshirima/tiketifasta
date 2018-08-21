<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/1/2018
 * Time: 7:22 PM
 */

namespace App\Services\Payments\Tigosecure;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

class TigoOnline
{
    use TigoOnlinePaymentC2B;
    /**
     * @return null
     */
    public function getAccessToken()
    {
        $token = $this->createAccessToken();
        if(isset($token)){
            return $token->accessToken;
        } else {
            return null;
        }
    }

    /**
     * @return mixed|null
     */
    public function getServerStatus(){
        return $this->serverStatus();
    }

    /**
     * @return mixed|null
     */
    public function createAccessToken(){

        $accessToken = null;

        $client = new Client();

        $url = 'https://secure.tigo.com/v1/oauth/generate/accesstoken?grant_type=client_credentials';//$this->getTigosecureUrl('/v1/oauth/generate/accesstoken?grant_type=client_credentials');

        $response = $client->request('POST', $url, $this->generateTokenOptions());

        if ($response->getStatusCode() == Response::HTTP_OK) {

            $accessToken = json_decode($response->getBody());

        } else {
            //Log failure

        }

        return $accessToken;
    }

    /**
     * @param $service
     * @return string
     */
    protected function getTigosecureUrl($service): string
    {
        if (env('TIGOSECURE_ENV') == 'production') {
            $url = env('TIGOSECURE_PRODUCTION') . $service;
        } else {
            $url = env('TIGOSECURE_TEST') . $service;
        }
        return $url;
    }

    /**
     * @return array
     */
    protected function generateTokenOptions(): array
    {
        return [
            'headers' =>
                [
                    'content-type' => 'application/x-www-form-urlencoded'
                ],
            'form_params'=>[
                'client_id' => env('TIGOSECURE_KEY2'),
                'client_secret' => env('TIGOSECURE_SECRET2'),
            ],

        ];
    }
}