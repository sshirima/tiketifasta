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
use App\Services\Payments\PaymentManager;
use App\Services\Payments\Tigosecure\TigoOnline;
use Illuminate\Http\Request;
use Exception;

class TigoOnlineController extends Controller
{
    private $tigoOnline;

    public function __construct(TigoOnline $tigoOnline)
    {
        $this->tigoOnline = $tigoOnline;
    }

    /**
     * @return null
     */
    public function generateAccessToken(){

       $accessToken = $this->tigoOnline->getAccessToken();

        return $accessToken;

    }

    /**
     * @return string
     */
    public function serverStatus(){

        try{
            $serverStatus = $this->tigoOnline->getServerStatus();
        }catch (Exception $exception){
            return $exception->getMessage();
        }

        return json_encode($serverStatus);
    }

    /**
     * @return string
     */
    public function validateAccount(Request $request){

        try{
            $input = $request->all();
            $response = $this->tigoOnline->validateMFSAccount('1300074238',$input['msisdn'],$input['firstname'], $input['lastname']);
        }catch (Exception $exception){
            return $exception->getMessage();
        };
        return json_encode($response);
    }

    public function authorizePayment(){
        try{
           $tigoOnlineC2B = TigoOnlineC2B::create([
               TigoOnlineC2B::COLUMN_REFERENCE => strtoupper(PaymentManager::random_code(12)),
               TigoOnlineC2B::COLUMN_PHONE_NUMBER =>'0654025111',
               TigoOnlineC2B::COLUMN_FIRST_NAME =>'Emmanuel',
               TigoOnlineC2B::COLUMN_LAST_NAME =>'Lenduyai',
               TigoOnlineC2B::COLUMN_EMAIL =>'elenduyai@yahoo.com',
               TigoOnlineC2B::COLUMN_AMOUNT =>'100',

           ]);

           $response = $this->tigoOnline->paymentAuthorization($tigoOnlineC2B);

           //$tigoOnlineC2B->delete();

        }catch (Exception $exception){
            return $exception->getMessage();
        }
        return json_encode($response);
    }

    public function confirmPayment(Request $request){

        //Find transaction with the given transactionId
        try{
            $input = $request->all();
            $transactionId = $input['transaction_ref_id'];
            if(isset($transactionId)){
                $transaction = TigoOnlineC2B::find($transactionId);

                if (isset($transaction)){

                    if ($transaction->access_code == $input['verification_code']){
                        $this->tigoOnline->confirmPayment($transaction, $request);
                        return 'Payment authorized and confirmed';
                    }
                    else
                    {
                        return 'Access code and verification code mismatch';
                    }
                }
                else {
                    return 'transaction not found with given Id';
                }

            } else {
                //Log transaction Id not set
                return 'transaction id not set';
            }
        }catch (Exception $ex){
            return $ex->getMessage();
        }
    }
}