<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/31/2018
 * Time: 9:31 PM
 */

namespace App\Services\Payments\Tigosecure;

use App\Models\TigoOnlineC2B;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

trait TigoOnlinePaymentC2B
{
    use TigoOnlineRequests;

    public function serverStatus()
    {
        $serverStatus = null;

        $client = new Client();

        $accessToken = $this->getAccessToken();

        $url = $this->getTigosecureUrl('/v1/tigo/systemstatus');

        if (isset($accessToken)) {
            $response = $client->request('GET', $url,$this->systemStatusOptions());

            if ($response->getStatusCode() == Response::HTTP_OK) {
                $serverStatus =  json_decode($response->getBody());
            } else {
                //Log error: Failed to retrieve server status

            }

        } else {
            //Log error: Cant not retrieve the access token

        }
        return $serverStatus;
    }

    public function paymentAuthorization(TigoOnlineC2B $tigoOnlineC2B)
    {

        $payment = null;

        $client = new Client();

        $accessToken = $this->getAccessToken();

        if (isset($accessToken)) {
            $this->saveAccessToken($tigoOnlineC2B, $accessToken);
            $url = 'https://secure.tigo.com/v1/tigo/payment-auth-test-2018/authorize';//env('MPESA_C2B_CONFIRM');
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('content-type: application/json','accessToken : '.$accessToken));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($this->paymentAuthorizationContent($tigoOnlineC2B, $accessToken)));
            $response = curl_exec($ch);
            // Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        //Confirm the transaction, generate ticket and send notification to user
                        $payment = $response;
                        break;
                    default:
                        echo 'Unexpected HTTP code: ', $http_code, "\n";
                }
            }
            curl_close($ch);
            /*$this->saveAccessToken($tigoOnlineC2B, $accessToken);

            $url = env('https://secure.tigo.com/v1/tigo/payment-auth-test-2018/authorize');//$this->getTigosecureUrl(env('TIGOSECURE_SRV_AUTHORIZE'));

            $response = $client->request('POST', $url,$this->paymentAuthorizationOptions($tigoOnlineC2B, $accessToken));

            if ($response->getStatusCode() == Response::HTTP_OK){
                //Save the transaction
                $this->completeAuthorization($tigoOnlineC2B, $response->getBody());
                $payment = json_decode($response->getBody());
            } else {
                //Log error: Failed to authorize payment
            }*/
        } else {
            //Log error: Cant not retrieve the access token
        }

        return $payment;
    }

    public function validateMFSAccount($transactionId, $msisdn, $firstname, $lastname){

        $accountStatus = null;

        $client = new Client();

        $accessToken = $this->getAccessToken();

        if (isset($accessToken)){

            $url = $this->getTigosecureUrl('/v1/tigo/mfs/validateMFSAccount');

            //dd($this->validateMFSAccountOptions($transactionId, $msisdn, $firstname, $lastname, $accessToken));
            $response = $client->request('POST', $url,$this->validateMFSAccountOptions($transactionId, $msisdn, $firstname, $lastname, $accessToken));
            if ($response->getStatusCode() == Response::HTTP_OK) {
                //Save the transaction
                $accountStatus = json_decode($response->getBody());
            } else {
                //Log error: Failed to retrieve account status

            }
        } else {
            //Log error: Cant not retrieve the access token

        }

        return $accountStatus;
    }

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     * @param $accessToken
     */
    protected function saveAccessToken(TigoOnlineC2B $tigoOnlineC2B, $accessToken)
    {
        $tigoOnlineC2B->access_token = $accessToken;
        $tigoOnlineC2B->update();
    }

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     * @param $payment
     */
    protected function completeAuthorization(TigoOnlineC2B $tigoOnlineC2B, $payment): void
    {
        $tigoOnlineC2B->auth_code = $payment['authCode'];
        $tigoOnlineC2B->update();
    }

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     * @param Request $request
     */
    protected function confirmPayment(TigoOnlineC2B $tigoOnlineC2B, Request $request){
        $input = $request->all();

        if($request->has('mfs_id')){
            $tigoOnlineC2B->mfs_id = $input['mfs_id'];
        }

        if($request->has('external_ref_id')){
            $tigoOnlineC2B->external_ref_id = $input['external_ref_id'];
        }

        $tigoOnlineC2B->status = TigoOnlineC2B::STATUS_SUCCESS;

        $tigoOnlineC2B->update();
    }
}