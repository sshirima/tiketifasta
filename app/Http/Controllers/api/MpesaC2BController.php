<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 8/13/2018
 * Time: 10:13 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Jobs\MpesaC2BTransactionProcessor;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Mpesa\MpesaTransactionC2B;
use App\Services\Tickets\TicketManager;
use Illuminate\Http\Request;
use Nathanmac\Utilities\Parser\Parser;
use Exception;
use Log;

class MpesaC2BController extends Controller
{
    use MpesaTransactionC2B;
    private $mpesa;

    public function __construct(Mpesa $mpesa)
    {
        $this->mpesa = $mpesa;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|string
     */
    public function validateMpesaC2BTransaction(Request $request)
    {
        $log_action = 'Receiving mpesa c2b post confirmation';
        $log_format_success = '%s, %s, %s';
        $log_format_fail = '%s, %s, %s, %s';
        $log_data = 'request:'.json_encode($request->getContent());
        try {
            Log::info(sprintf($log_format_success,$log_action,'success',$log_data). PHP_EOL);

            $parser = new Parser();

            MpesaC2BTransactionProcessor::dispatch($parser->xml($request->getContent()));

        } catch (Exception $ex) {
            $log_event = 'exception:'.$ex->getMessage();
            Log::error(sprintf($log_format_fail,$log_action,'fail',$log_event,$log_data). PHP_EOL);
        }

        return response($this->getMpesaC2BAuthorizationResponse($request), 200, ['Content-type' => 'application/xml']);
    }
}
