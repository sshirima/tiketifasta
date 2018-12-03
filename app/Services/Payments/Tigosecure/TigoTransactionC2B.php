<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/31/2018
 * Time: 9:31 PM
 */

namespace App\Services\Payments\Tigosecure;

use App\Models\TigoOnlineC2B;
use App\Services\DateTime\DatesOperations;
use App\Services\SMS\SendSMS;
use App\Services\Tickets\TicketManager;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

trait TigoTransactionC2B
{
    use TigoTransactionC2BRequests, DatesOperations, TicketManager, SendSMS;

    public function serverStatus()
    {
        $serverStatus = null;

        $client = new Client();

        $accessToken = $this->getAccessToken();

        //$url = $this->getTigosecureUrl('/v1/tigo/systemstatus');

        $url = 'https://secure.tigo.com/v1/tigo/systemstatus';

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

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     * @return array|null
     */
    public function paymentAuthorization(TigoOnlineC2B $tigoOnlineC2B)
    {

        $payment = null;

        $accessToken = $this->getAccessToken();

        if (isset($accessToken)) {
            $this->saveAccessToken($tigoOnlineC2B, $accessToken);
            //$url = 'https://secure.tigo.com/v1/tigo/payment-auth-test-2018/authorize';//env('MPESA_C2B_CONFIRM');
            $url = 'https://secure.tigo.com/v1/tigo/payment-auth/authorize';
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('content-type: application/json','accessToken : '.$accessToken));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $postFields = json_encode($this->paymentAuthorizationContent($tigoOnlineC2B, $accessToken));
            curl_setopt( $ch, CURLOPT_POSTFIELDS,$postFields);
            $response = curl_exec($ch);
            // Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        //Confirm the transaction, generate ticket and send notification to user
                        //$payment = $response;
                        $payment = ['status_code'=>$http_code, 'response'=>$response];
                        break;
                    default:
                        $payment = ['status_code'=>$http_code, 'response'=>$response];
                }
            }
            curl_close($ch);
        } else {
            return $payment;
        }

        return $payment;
    }

    /**
     * @param $transactionId
     * @param $msisdn
     * @param $firstname
     * @param $lastname
     * @return array|null
     */
    public function validateMFSAccount($transactionId, $msisdn, $firstname, $lastname){

        $accountStatus = null;

        $accessToken = $this->getAccessToken();

        if (isset($accessToken)){

            //$url = $this->getTigosecureUrl('/v1/tigo/mfs/validateMFSAccount');

            $url = 'https://secure.tigo.com/v1/tigo/mfs/validateMFSAccount';

            //$url = 'https://secure.tigo.com/v1/tigo/mfs/validateMFSAccount-test-2018';
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('content-type: application/json','accessToken : '.$accessToken));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

            $postFields = json_encode($this->validateMFSAccountContent($transactionId, $msisdn, $firstname, $lastname));

            curl_setopt( $ch, CURLOPT_POSTFIELDS,$postFields);
            $response = curl_exec($ch);

            // Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        //Confirm the transaction, generate ticket and send notification to user
                        //$payment = $response;
                        $accountStatus = ['status_code'=>$http_code, 'response'=>$response];
                        break;
                    default:
                        $accountStatus = ['status_code'=>$http_code, 'response'=>$response];
                }
            }
            curl_close($ch);
        } else {
            return $accountStatus;
        }

        return $accountStatus;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function confirmTigoSecureC2BTransaction(Request $request)
    {
        $transaction = null;

        try {
            $input = $request->all();

            //Check if successful
            $transactionId = $input['transaction_ref_id'];
            $transaction = TigoOnlineC2B::with(['bookingPayment', 'bookingPayment.booking', 'bookingPayment.booking.schedule.day',
                'bookingPayment.booking.trip', 'bookingPayment.booking.trip.bus', 'bookingPayment.booking.trip.bus.merchant',
                'bookingPayment.booking.trip.bus', 'bookingPayment.booking.seat'])->where(['reference' => $transactionId])->first();

            if (!isset($transaction)) {
                $error = 'Transaction not found with ID=' . $transactionId;
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                return ['status'=>false, 'error'=>$error];
            }

            if (!($input['trans_status'] == 'success')) {

                $error = 'Transaction failed with errorCode:'.$input['error_code'];
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                $transaction->status = TigoOnlineC2B::STATUS_FAIL;
                $transaction->error_code = $input['error_code'];
                $transaction->update();


                return ['status'=>false, 'error'=>$error];

            }

            if(!(array_key_exists('verification_code', $input))){
                $error = 'Transaction failed, verification code not provided';
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                return ['status'=>false, 'error'=>$error];
            }

            if (!($transaction->access_token == $input['verification_code'])) {
                $error = 'Access code and verification code mismatch';
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                return ['status'=>false, 'error'=>$error];
            }

            $this->updateMfsParameters($transaction, $request);

            return ['status'=>true, 'model'=>$transaction];

        } catch (\Exception $ex) {
            //return $transaction;
            $error = 'An exception was thrown on TigoTransactionC2B:confirmTigoSecureB2CTransaction, message='.$ex->getMessage();
            Log::channel('tigosecurec2b')->error($error . PHP_EOL);
            return ['status'=>false, 'error'=>'Something went wrong during processing'];
        }
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
    public function updateMfsParameters(TigoOnlineC2B $tigoOnlineC2B, Request $request){
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