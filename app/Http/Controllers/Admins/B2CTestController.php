<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\B2CTestRequest;
use App\Http\Requests\Admin\TigoSendCashRequest;
use App\Models\MpesaB2C;
use App\Models\TigoB2C;
use App\Services\Payments\Mpesa\MpesaTransactionB2C;
use App\Services\Payments\PaymentManager;
use App\Services\Payments\Tigosecure\TigoTransactionB2C;
use App\Services\SMS\SendSMS;
use Illuminate\Http\Request;

class B2CTestController extends BaseController
{

    use TigoTransactionB2C,MpesaTransactionB2C, SendSMS;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendCash(){
        return view('admins.pages.payments.mpesa.mpesaB2C_send_cash');

    }

    public function sendCashSubmit(B2CTestRequest $request){
        $input = $request->all();

        $numberCheck = $this->confirmReceiverNumber($input['receiver']);

        if(!$numberCheck['status']){
            return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpIsSent'=>false, 'error'=>$numberCheck['error']]);
        }

        $receiver = $numberCheck['number'];
        $operator = $request->get('operator');

        if ($operator == 'mpesa'){
            $mpesaB2C = MpesaB2C::create([
                'amount' => $input['amount'],
                'command_id' => 'BusinessPayment',
                'initiator' => config('payments.mpesa.b2c.initiator'),
                'recipient' => $receiver,
                'og_conversation_id' => strtoupper(PaymentManager::random_code(16)),
                'transaction_date' => PaymentManager::getCurrentTimestamp(),
                'transaction_id' => strtoupper(PaymentManager::random_code(8)),
            ]);
            \Session::put('transaction_reference',$mpesaB2C->transaction_id);
        }

        if ($operator == 'tigopesa'){
            $tigoB2C = TigoB2C::create([
                'type' => config('payments.tigo.bc2.type'),
                'reference_id' => strtoupper(PaymentManager::random_code(8)),
                'msisdn' => config('payments.tigo.bc2.mfi'),
                'pin' => config('payments.tigo.bc2.pin'),
                'msisdn1' => $receiver,
                'amount' => $input['amount'],
                'language' => config('payments.tigo.bc2.language'),
            ]);
            \Session::put('transaction_reference',$tigoB2C->reference_id);
        }
        \Session::put('transaction_operator',$operator);

        //$tigoB2C = $this->createTigoB2CModel($receiver,$input['amount']);

        //Generate and Send OTP
        $otp = rand(10000, 99999);

        \Session::put('otp',$otp);
        
        $phoneNumber = config('payments.tigo.bc2.confirm_otp');
        $message = sprintf(config('payments.tigo.bc2.otp_message'), $otp);

        $sendSMS = $this->sendOneSMS('tigopesa',$phoneNumber, $message);

        if ($sendSMS['status']){
            //return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>true]);
            return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpIsSent'=>true]);
        }else{
            return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpIsSent'=>false, 'error'=>$sendSMS['error']]);
            //return view('admins.pages.sms.send_sms')->with(['sentStatus'=>false,'error'=>$sendSMS['error']]);
        }
    }

    /**
     * @param Request $request
     * @return string
     */
    public function verifyTransactionOTP(Request $request){

        $input = $request->all();

        $enteredOtp = $input['otp'];
        $OTP = $request->session()->get('otp');

        //Removing Session variable
        $request->session()->forget('otp');
        $operator = $request->session()->get('transaction_operator');

        if ($enteredOtp == $OTP){

            $reference = $request->session()->get('transaction_reference');


            if($operator == 'tigopesa'){
                $tigoB2C = TigoB2C::where(['reference_id'=>$reference])->first();
                if (!isset($tigoB2C)){
                    $request->session()->forget('transaction_reference');
                    return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>false,'moneySent'=>false,'error'=>'Fail to retrieve transaction']);
                }
                $request->session()->forget('transaction_reference');
                $request->session()->forget('otp');
                $request->session()->forget('otp_entry_count');
                $request->session()->forget('transaction_reference');

                $response = $this->postTigoB2CTransaction($tigoB2C);

                //No comments
                if ($response['status'] == true) {
                    return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>true,'moneySent'=>true, 'response'=>$response['response']]);
                    //return 'Success : ' . json_encode($response['response']);
                } else {
                    return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>true,'moneySent'=>false,'response'=>$response['error']]);
                    //return 'Error : ' . json_encode($response['error']);
                }
            }

            if($operator == 'mpesa'){
                $mpesaB2C = MpesaB2C::where(['transaction_id'=>$reference])->first();
                if (!isset($mpesaB2C)){
                    $request->session()->forget('transaction_reference');
                    return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpVerified'=>false,'moneySent'=>false,'error'=>'Fail to retrieve transaction']);
                }
                $request->session()->forget('transaction_reference');
                $request->session()->forget('otp');
                $request->session()->forget('otp_entry_count');
                $request->session()->forget('transaction_reference');

                $response = $this->postTransactionMpesaB2C($mpesaB2C);

                //No comments
                if ($response['status'] == true) {
                    return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpVerified'=>true,'moneySent'=>true, 'response'=>$response['response']]);
                    //return 'Success : ' . json_encode($response['response']);
                } else {
                    return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpVerified'=>true,'moneySent'=>false,'response'=>$response['error']]);
                    //return 'Error : ' . json_encode($response['error']);
                }
            }

        } else {
            $count = 1;
            $maxAttempt = (int)config('payments.tigo.bc2.otp_max_reentry');

            if($request->session()->has('otp_entry_count')){

                $count = (int)$request->session()->get('otp_entry_count');
                $count++;

                if ($count < $maxAttempt){

                    $request->session()->put('otp_entry_count', $count);

                } else {
                    $request->session()->forget('otp_entry_count');
                    return redirect(route('admin.mpesab2c.send_cash'));
                }

            } else {
                $request->session()->put('otp_entry_count', $count);
            }

            if($operator == 'mpesa'){
                return view('admins.pages.payments.mpesa.mpesaB2C_send_cash')->with(['otpIsSent'=>true, 'otpVerified'=>false,
                    'reentryCount'=>($maxAttempt-$count)]);
            } else if ($operator == 'tigopesa'){
                return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>true, 'otpVerified'=>false,
                    'reentryCount'=>($maxAttempt-$count)]);
            }
        }
        return $operator;
    }
}