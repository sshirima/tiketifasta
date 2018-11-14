<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/10/2018
 * Time: 10:47 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\TigoOnlineC2B;
use App\Services\DateTime\DatesOperations;
use App\Services\Payments\PaymentManager;
use App\Services\Payments\Tigosecure\TigoOnline;
use App\Services\SMS\SendSMS;
use App\Services\Tickets\TicketManager;
use Illuminate\Http\Request;
use Exception;
use App\Services\SMS\Smpp;
use Log;

class TigoOnlineController extends Controller
{
    use DatesOperations, TicketManager, SendSMS;

    private $tigoOnline;

    public function __construct(TigoOnline $tigoOnline)
    {
        $this->tigoOnline = $tigoOnline;
    }

    /**
     * @return null
     */
    public function generateAccessToken()
    {

        $accessToken = $this->tigoOnline->getAccessToken();

        return $accessToken;

    }

    /**
     * @return string
     */
    public function serverStatus()
    {

        try {
            $serverStatus = $this->tigoOnline->getServerStatus();
        } catch (Exception $exception) {
            return $exception->getMessage();
        }

        return json_encode($serverStatus);
    }

    /**
     * @return string
     */
    public function validateAccount(Request $request)
    {

        try {
            $input = $request->all();

            $tigoOnline = TigoOnlineC2B::where(['reference'=>$input['reference']])->first();

            if (isset($tigoOnline)){
                $response = $this->tigoOnline->validateMFSAccount($tigoOnline->reference, $tigoOnline->phone_number, $tigoOnline->firstname, $tigoOnline->lastname);
            } else {
                $response = 'Transaction not found: Id='.$input['reference'];
            }
        } catch (Exception $exception) {
            return $exception->getMessage();
        };
        return json_encode($response);
    }

    public function authorizePayment(Request $request)
    {
        try {
            $input = $request->all();
            $tigoOnlineC2B = TigoOnlineC2B::create([
                TigoOnlineC2B::COLUMN_REFERENCE => strtoupper(PaymentManager::random_code(12)),
                TigoOnlineC2B::COLUMN_PHONE_NUMBER => $input['msisdn'],
                TigoOnlineC2B::COLUMN_FIRST_NAME => $input['firstname'],
                TigoOnlineC2B::COLUMN_LAST_NAME => $input['lastname'],
                TigoOnlineC2B::COLUMN_TAX =>'0',
                TigoOnlineC2B::COLUMN_FEE => '0',
                TigoOnlineC2B::COLUMN_AMOUNT => $input['amount'],
            ]);

            $response = $this->tigoOnline->paymentAuthorization($tigoOnlineC2B);

            if ($response['status_code'] == 200) {
                $res = json_decode($response['response']);
                if (isset($res->authCode)) {
                    $tigoOnlineC2B->auth_code = $res->authCode;
                    $tigoOnlineC2B->authorized_at = $this->convertDate($tigoOnlineC2B->creationDateTime,'Y-m-d H:i:s');
                    $tigoOnlineC2B->update();
                    return redirect($res->redirectUrl);
                } else {
                    //Log auth code not found
                    Log::channel('tigosecurec2b')->error('Auth code not found[' . 'Response:' . $response['response'] . ']' . PHP_EOL);
                    return '';
                }

            } else {
                //Log Payment authorization failed
                Log::channel('tigosecurec2b')->error('Authorization failed[' . 'StatusCode:' . $response['status_code'] . ']' . PHP_EOL);
                return json_encode($response);
            }
        } catch (Exception $exception) {
            Log::error($exception->getTraceAsString());
            return $exception->getMessage();
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function confirmPayment(Request $request)
    {
        try {
            $input = $request->all();

            if($input['trans_status'] == 'success'){
                $transactionId = $input['transaction_ref_id'];
                $transaction = TigoOnlineC2B::with(['bookingPayment','bookingPayment.booking'])->where(['reference'=>$transactionId])->first();
                if (isset($transaction)) {
                    if ($transaction->access_token == $input['verification_code']) {
                        $this->tigoOnline->confirmTigoSecurePaymentC2B($transaction, $request);
                        $booking = $transaction->bookingPayment->booking;
                        $bookingPayment = $transaction->bookingPayment;
                        $ticket = $this->createTicket($bookingPayment);
                        $booking->confirmBooking();
                        $this->confirmTicket($ticket);
                        $this->sendTicketReference($ticket, $ticket->booking->payment);
                        return view('users.pages.bookings.booking_confirmation')->with(['ticket'=>$ticket,'bookingPayment' => $bookingPayment, 'booking' => $booking]);
                    } else {
                        $error = 'Access code and verification code mismatch';
                        return view('users.pages.bookings.booking_confirmation')->with(['error' => $error]);
                    }
                } else {
                    $error = 'Transaction not found with given Id';
                    return view('users.pages.bookings.booking_confirmation')->with(['error' => $error]);
                }

            } else {
                Log::channel('tigosecurec2b')->error('Transaction confirmation failed[' . 'TransactionRef:' .
                    $input['transaction_ref_id'] . ', ErrorCode:'.$input['error_code'].']' . PHP_EOL);

                $error = 'Transaction failed : '.$this->tigoOnline->errorCategory($input['error_code']);

                return view('users.pages.bookings.booking_confirmation')->with(['error' => $error]);
                //return $this->tigoOnline->errorCategory($input['error_code']);
            }
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }
}