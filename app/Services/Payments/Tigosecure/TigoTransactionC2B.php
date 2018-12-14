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
use App\Services\Tickets\TicketManager;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

trait TigoTransactionC2B
{
    use TigoTransactionC2BRequests, DatesOperations, TicketManager;

    /**
     * @return null
     */
    public function generateAccessToken()
    {
        $responseArray=null;
        $log_action = 'Generate tigo_secure access token';
        $log_data ='';
        $log_format_success = '%s,%s,%s,%s';
        $log_format_fail = '%s,%s,%s';
        $ch = curl_init();

        try{

            $url = config('payments.tigo.c2b.url_token');

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type' => 'application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $postFields = $this->accessTokenRequestParameters();

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

            curl_setopt($ch, CURLOPT_TIMEOUT, config('payments.mpesa.b2c.timeout'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('payments.mpesa.b2c.connect_timeout'));

            $response = curl_exec($ch);

            if ($response === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    $log_status = 'fail';
                    $log_event = 'connection timed out:'.$url;
                    Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,''). PHP_EOL);
                    return ['status'=>false,'error'=>$log_event];
                }
            }
            // Check HTTP status code
            if (!curl_errno($ch)) {
                $log_data = $log_data .',response:'.$response;
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        $log_status = 'success';
                        Log::info(sprintf($log_format_success,$log_action,$log_status,''). PHP_EOL);
                        $res = json_decode($response);
                        $responseArray= ['status'=>true,'accessToken'=>$res->accessToken];
                        break;
                    default:
                        $log_status = 'fail';
                        $log_event = 'unexpected HTTP code:'.$http_code;
                        Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                        $responseArray= ['status'=>false,'error'=>$log_event];
                }
            } else {
                $log_status = 'fail';
                $log_event = 'curl error:'.curl_errno($ch);
                Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                $responseArray = array('status' => false, 'error' => $log_event);
            }


        }catch (\Exception $ex){
            $log_status = 'fail';
            $log_event = 'exception:'.$ex->getMessage();
            Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
            $responseArray = ['status'=>false,'error'=>$log_event];
        }
        curl_close($ch);
        return $responseArray;
    }

    public function serverStatus()
    {
        $serverStatus = null;

        $client = new Client();

        $accessToken = $this->getAccessToken();

        //$url = $this->getTigosecureUrl('/v1/tigo/systemstatus');

        $url = 'https://secure.tigo.com/v1/tigo/systemstatus';

        if (isset($accessToken)) {
            $response = $client->request('GET', $url, $this->systemStatusOptions());

            if ($response->getStatusCode() == Response::HTTP_OK) {
                $serverStatus = json_decode($response->getBody());
            } else {
                //Log error: Failed to retrieve server status
            }

        } else {
            //Log error: Cant not retrieve the access token

        }
        return $serverStatus;
    }

    /**
     * @param $bookingPayment
     * @return array
     */
    public function authorizeTigoC2BTransaction($bookingPayment)
    {
        $responseArray=null;
        $log_action = 'Authorize tigosecure c2b transaction';
        $log_data = '';
        $log_format_fail = '%s,%s,%s,%s';
        $log_format_success = '%s,%s,%s';
        $ch = curl_init();
        try{
            $tokenResponse = $this->generateAccessToken();

            if(!$tokenResponse['status']){
                curl_close($ch);
                return ['status'=>false, 'error'=>$tokenResponse['error']];
            }

            $accessToken = $tokenResponse['accessToken'];

            $tigoC2B = $this->createTigoC2B($bookingPayment);

            $this->saveAccessToken($tigoC2B, $accessToken);

            $url = config('payments.tigo.c2b.url_authorize');
            $postFields = json_encode($this->paymentAuthorizationContent($tigoC2B, $accessToken));

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'accessToken : ' . $accessToken));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

            curl_setopt($ch, CURLOPT_TIMEOUT, config('payments.mpesa.b2c.timeout'));
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config('payments.mpesa.b2c.connect_timeout'));

            $response = curl_exec($ch);

            if ($response === false) {
                $info = curl_getinfo($ch);
                if ($info['http_code'] === 0) {
                    $log_status = 'fail';
                    $log_event = 'connection timed out:'.$url;
                    Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,''). PHP_EOL);
                }
            }
            // Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        $log_status = 'success';
                        Log::info(sprintf($log_format_success,$log_action,$log_status,'reference:'.$tigoC2B->reference). PHP_EOL);
                        $res = json_decode($response);
                        $this->completeAuthorization($tigoC2B, $res);
                        $responseArray = ['status'=>true, 'redirectUrl'=>$res->redirectUrl];
                        break;
                    default:
                        $log_status = 'fail';
                        $log_event = 'unexpected HTTP code:'.$http_code;
                        Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                        $responseArray = ['status'=>true,'error'=>'Unexpected response from server: http_code='.$http_code];

                }
            } else {
                $log_status = 'fail';
                $log_event = 'curl error:'.curl_errno($ch);
                Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
                $responseArray = ['status'=>false,'error'=>$log_event];
            }


        }catch (\Exception $ex){
            $log_status = 'fail';
            $log_event = 'exception:'.$ex->getMessage();
            Log::error(sprintf($log_format_fail,$log_action,$log_status,$log_event,$log_data). PHP_EOL);
            $responseArray = ['status'=>false,'error'=>$log_event];;
        }
        curl_close($ch);
        return $responseArray;
    }

    /**
     * @param $bookingPayment
     * @return mixed
     */
    protected function createTigoC2B($bookingPayment){
        $booking = $bookingPayment->booking;

        return TigoOnlineC2B::create($this->getTigoC2BParameterArray($bookingPayment, $booking));
    }

    /**
     * @param $transactionId
     * @param $msisdn
     * @param $firstname
     * @param $lastname
     * @return array|null
     */
    public function validateMFSAccount($transactionId, $msisdn, $firstname, $lastname)
    {

        $accountStatus = null;

        $accessToken = $this->getAccessToken();

        if (isset($accessToken)) {

            //$url = $this->getTigosecureUrl('/v1/tigo/mfs/validateMFSAccount');

            $url = 'https://secure.tigo.com/v1/tigo/mfs/validateMFSAccount';

            //$url = 'https://secure.tigo.com/v1/tigo/mfs/validateMFSAccount-test-2018';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type: application/json', 'accessToken : ' . $accessToken));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $postFields = json_encode($this->validateMFSAccountContent($transactionId, $msisdn, $firstname, $lastname));

            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            $response = curl_exec($ch);

            // Check HTTP status code
            if (!curl_errno($ch)) {
                switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                    case 200:
                        //Confirm the transaction, generate ticket and send notification to user
                        //$payment = $response;
                        $accountStatus = ['status_code' => $http_code, 'response' => $response];
                        break;
                    default:
                        $accountStatus = ['status_code' => $http_code, 'response' => $response];
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

                return ['status' => false, 'error' => $error];
            }

            if (!($input['trans_status'] == 'success')) {

                $error = 'Transaction failed with errorCode:' . $input['error_code'];
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                $transaction->status = TigoOnlineC2B::STATUS_FAIL;
                $transaction->transaction_status = TigoOnlineC2B::TRANS_STATUS_FAILED;
                $transaction->error_code = $input['error_code'];
                $transaction->update();


                return ['status' => false, 'error' => $error, 'model' => $transaction];

            }

            if (!(array_key_exists('verification_code', $input))) {
                $error = 'Transaction failed, verification code not provided';
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                return ['status' => false, 'error' => $error, 'model' => $transaction];
            }

            if (!($transaction->access_token == $input['verification_code'])) {
                $error = 'Access code and verification code mismatch';
                Log::channel('tigosecurec2b')->error($error . PHP_EOL);

                return ['status' => false, 'error' => $error, 'model' => $transaction];
            }

            $this->updateMfsParameters($transaction, $request);

            return ['status' => true, 'model' => $transaction];

        } catch (\Exception $ex) {
            //return $transaction;
            $error = 'An exception was thrown on TigoTransactionC2B:confirmTigoSecureB2CTransaction, message=' . $ex->getMessage();
            Log::channel('tigosecurec2b')->error($error . PHP_EOL);
            return ['status' => false, 'error' => 'Something went wrong during processing'];
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
     * @param $response
     */
    protected function completeAuthorization(TigoOnlineC2B $tigoOnlineC2B, $response): void
    {
        $tigoOnlineC2B->auth_code = $response->authCode;
        $tigoOnlineC2B->transaction_status = TigoOnlineC2B::TRANS_STATUS_AUTHORIZED;
        $tigoOnlineC2B->update();
    }

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     * @param Request $request
     */
    public function updateMfsParameters(TigoOnlineC2B $tigoOnlineC2B, Request $request)
    {
        $input = $request->all();

        if ($request->has('mfs_id')) {
            $tigoOnlineC2B->mfs_id = $input['mfs_id'];
        }

        if ($request->has('external_ref_id')) {
            $tigoOnlineC2B->external_ref_id = $input['external_ref_id'];
        }

        $tigoOnlineC2B->status = TigoOnlineC2B::STATUS_SUCCESS;
        $tigoOnlineC2B->transaction_status = TigoOnlineC2B::TRANS_STATUS_SETTLED;

        $tigoOnlineC2B->update();
    }

    /**
     * @param $bookingPayment
     * @param $booking
     * @return array
     */
    protected function getTigoC2BParameterArray($bookingPayment, $booking): array
    {
        return [
            TigoOnlineC2B::COLUMN_REFERENCE => $bookingPayment->payment_ref,//strtoupper(PaymentManager::random_code(12)),
            TigoOnlineC2B::COLUMN_PHONE_NUMBER => $booking->phonenumber,
            TigoOnlineC2B::COLUMN_FIRST_NAME => $booking->firstname,
            TigoOnlineC2B::COLUMN_LAST_NAME => $booking->lastname,
            TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID => $bookingPayment->id,
            TigoOnlineC2B::COLUMN_TAX => '0',
            TigoOnlineC2B::COLUMN_FEE => '0',
            TigoOnlineC2B::COLUMN_AMOUNT => $booking->price,
        ];
    }

    /**
     * @return array
     */
    protected function accessTokenRequestParam(): array
    {
        return [
            'headers' =>
                [
                    'content-type' => 'application/x-www-form-urlencoded'
                ],
            'form_params' => [
                'client_id' => config('payments.tigo.c2b.key'),
                'client_secret' => config('payments.tigo.c2b.secret'),
            ],

        ];
    }
}