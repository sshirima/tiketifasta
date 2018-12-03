<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/1/2018
 * Time: 7:22 PM
 */

namespace App\Services\Payments\Tigosecure;

use App\Models\TigoOnlineC2B;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Log;

class TigoOnline
{
    use TigoTransactionC2B;

    const ERROR_CODE_2501 = 'purchase-3008-2501-F';
    const ERROR_CODE_0000 = 'purchase-3008-0000-S';
    const ERROR_CODE_2502 = 'purchase-3008-2502-F';
    const ERROR_CODE_3011 = 'purchase-3008-3011-E';
    const ERROR_CODE_3043 = 'purchase-3008-3043-E';
    const ERROR_CODE_3045 = 'purchase-3008-3045-E';

    public function initializePaymentC2B(array $attributes)
    {
        return TigoOnlineC2B::create($attributes);
    }

    /**
     * @return null
     */
    public function getAccessToken()
    {
        $token = $this->createAccessToken();
        if (isset($token)) {
            return $token->accessToken;
        } else {
            return null;
        }
    }

    /**
     * @return mixed|null
     */
    public function getServerStatus()
    {
        return $this->serverStatus();
    }

    /**
     * @return mixed|null
     */
    public function createAccessToken()
    {

        $accessToken = null;

        $client = new Client();

        $url = 'https://secure.tigo.com/v1/oauth/generate/accesstoken?grant_type=client_credentials';//$this->getTigosecureUrl('/v1/oauth/generate/accesstoken?grant_type=client_credentials');

        //$url = 'https://secure.tigo.com/v1/oauth/generate/accesstoken-test-2018?grant_type=client_credentials';

        $response = $client->request('POST', $url, $this->generateTokenOptions());

        if ($response->getStatusCode() == Response::HTTP_OK) {

            $accessToken = json_decode($response->getBody());

        } else {
            //Log failure
            Log::channel('tigosecurec2b')->error('Payment authorization>Failed to retrieve access token' . PHP_EOL);
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
            'form_params' => [
                'client_id' => env('TIGOSECURE_KEY'),
                'client_secret' => env('TIGOSECURE_SECRET'),
            ],

        ];
    }

    /**
     * @param $errorCode
     * @return string
     */
    public function errorCodeDescription($errorCode)
    {
        if (strcasecmp($errorCode, self::ERROR_CODE_2501)) {
            return 'Backend system error';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_2502)) {
            return 'Transaction timed out';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3011)) {
            return 'Unable to complete transaction invalid amount';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3043)) {
            return 'Transaction not authorize';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3045)) {
            return 'Cancel Transaction';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_0000)) {
            return 'Successful Payment';
        } else {
            return 'No description on error code:' . $errorCode;
        }
    }

    /**
     * @param $errorCode
     * @return string
     */
    public function errorCategory($errorCode)
    {
        if (strcasecmp($errorCode, self::ERROR_CODE_2501)) {
            return 'Backend Error caused the transaction to fail';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_2502)) {
            return 'The transaction timed out causing it to fail';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3011)) {
            return 'Unable to complete transaction as amount is invalid';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3043)) {
            return 'The customer did not authorize the payment and therefore the transaction failed. This could be caused by the customer not confirming payment, incorrect verification code or PIN code, insufficient balance etc';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_3045)) {
            return 'The customer doesn’t wish to complete the transaction and wants to cancel the transaction at its current state';
        } else if (strcasecmp($errorCode, self::ERROR_CODE_0000)) {
            return 'Payment was successful';
        } else {
            return 'No category description on error code:' . $errorCode;
        }
    }
}