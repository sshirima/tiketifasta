<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/13/2018
 * Time: 10:13 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Jobs\ValidateMpesaC2B;
use App\Models\MpesaC2B;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\Mpesa\Mpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nathanmac\Utilities\Parser\Parser;
use Exception;
class MpesaC2BController extends Controller
{
use AuthorizeBooking;
    private $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    public function keys(){
        //return Storage::disk('mpesa')->get('tkj.vodacom.co.tz.key');
    }

    public function validatePaymentC2B(Request $request){

        $parser = new Parser();
        $input = $parser->xml($request->getContent());

        $response = $this->mpesa->validatePaymentResponseC2B([
            'conversationID'=>$input['request']['transaction']['conversationID'],
            'originatorConversationID'=>['request']['transaction']['originatorConversationID'],
            'responseCode'=>0,
            'responseDesc'=>'Received',
            'serviceStatus'=>'Success',
            'transactionID'=>['request']['transaction']['transactionID'],
        ]);

        try{

            ValidateMpesaC2B::dispatch($request->getContent());

        }catch (Exception $ex){
            return response($response, 200, ['Content-type'=>'application/xml']);
        }

        return response($response, 200, ['Content-type'=>'application/xml']);
    }
}
