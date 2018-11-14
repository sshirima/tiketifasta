<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/7/2018
 * Time: 9:53 PM
 */

namespace App\Http\Controllers\Admins;

use App\Http\Requests\Admin\TigoSendCashRequest;
use App\Models\TigoB2C;
use App\Services\Payments\TigoUSSD\TigoPaymentB2C;
use App\Services\SMS\SendSMS;
use Illuminate\Http\Request;
use Okipa\LaravelBootstrapTableList\TableList;

class TigoB2CController extends BaseController
{

    use TigoPaymentB2C, SendSMS;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request){

        $table = $this->createDisplayTable();

        return view('admins.pages.payments.tigoB2C_index')->with(['table'=>$table]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendCash(){
        return view('admins.pages.payments.tigoB2C_send_cash');

    }

    /**
     * @param TigoSendCashRequest $request
     * @return array
     */
    public function sendCashSubmit(TigoSendCashRequest $request){
        $input = $request->all();

        $numberCheck = $this->checkNumber($input['receiver']);

        if(!$numberCheck['status']){
            return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>false, 'error'=>$numberCheck['error']]);
        }

        $receiver = $numberCheck['number'];

        $tigpB2C = $this->createTigoB2CModel($receiver,$input['amount']);

        //Generate and Send OTP
        $otp = rand(1000, 9999);

        \Session::put('otp',$otp);
        \Session::put('tigob2cReferenceId',$tigpB2C->reference_id);

        $phoneNumber = config('payments.tigo.bc2.confirm_otp');
        $message = sprintf(config('payments.tigo.bc2.otp_message'), $otp);

        $sendSMS = $this->sendOneSMS('tigopesa',$phoneNumber, $message);

        if ($sendSMS['status']){
            //return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>true]);
            return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>true]);
        }else{
            return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpIsSent'=>false, 'error'=>$sendSMS['error']]);
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
        \Session::forget('otp');

        if ($enteredOtp == $OTP){

            $reference = $request->session()->get('tigob2cReferenceId');

            $tigoB2C = TigoB2C::where(['reference_id'=>$reference])->first();

            if (!isset($tigoB2C)){
                \Session::forget('tigob2cReferenceId');
                return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>false,'moneySent'=>false,'error'=>'Fail to retrieve transaction']);
            }

            $response = $this->initiatePayment($tigoB2C->msisdn1, $tigoB2C->amount);

            //No comments
            if ($response['status'] == true) {
                return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>true,'moneySent'=>true, 'response'=>$response['response']]);
                //return 'Success : ' . json_encode($response['response']);
            } else {
                return view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>false,'moneySent'=>false,'error'=>$response['error']]);
                //return 'Error : ' . json_encode($response['error']);
            }

        } else {
            return back()->with(['otpIsSent'=>true, 'error'=>'OTP verification failed']);

            //view('admins.pages.payments.tigoB2C_send_cash')->with(['otpVerified'=>false,'moneySent'=>false,'error'=>'OTP verification failed']);
            //return 'Fail to verify OTP';
        }
    }

    /**
     * @return mixed
     */
    protected function createDisplayTable()
    {
        $table = app(TableList::class)
            ->setModel(TigoB2C::class)
            ->setRowsNumber(10)
            ->enableRowsNumberSelector()
            ->setRoutes([
                'index' => ['alias' => 'admin.tigo_b2c.index', 'parameters' => []],
            ])->addQueryInstructions(function ($query) {
                $query->select('tigo_b2c.id as id','reference_id','msisdn1','amount','txn_status','txn_message',
                    'txn_id','tigo_b2c.created_at','tigo_b2c.updated_at');
            });

        $table = $this->setTableColumns($table);

        return $table;
    }

    /**
     * @param $table
     * @return mixed
     */
    private function setTableColumns($table)
    {
        $table->addColumn('msisdn1')->setTitle('Receiver')->isSearchable();
        $table->addColumn('amount')->setTitle('Reference');
        $table->addColumn('txn_id')->setTitle('Transaction Id');
        $table->addColumn('txn_status')->setTitle('Status')->isSearchable();
        $table->addColumn('txn_message')->setTitle('Status message')->isSearchable();
        $table->addColumn('updated_at')->setTitle('Updated at')->isSortable()->isSearchable();
        $table->addColumn('created_at')->setTitle('Created at')->isSortable()->isSearchable()->sortByDefault('desc');

        /*$table->addColumn('status')->setTitle('Status')->isCustomHtmlElement(function($entity, $column){
            return $entity['status'] == Booking::STATUS_CONFIRMED?
                '<div class="label label-success">'.'Paid'.'</div>':'<div class="label label-warning">'.$entity['status'].'</div>';
        });*/
        return $table;
    }
}