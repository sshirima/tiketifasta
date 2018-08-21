<?php

namespace App\Jobs;

use App\Models\BookingPayment;
use App\Models\MpesaC2B;
use App\Services\Payments\Mpesa\Mpesa;
use App\Services\Payments\Mpesa\xml\MpesaC2BData;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ConfirmMpesaC2B implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, MpesaC2BData;

    private $mpesaC2B;
    private $bookingPayment;


    public function __construct(MpesaC2B $mpesaC2B, BookingPayment $bookingPayment)
    {
        $this->mpesaC2B = $mpesaC2B;
        $this->bookingPayment = $bookingPayment;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /*$client = new Client();

        $url = env('MPESA_C2B_CONFIRM');

        $response = $client->request('POST', $url,$this->getBodyContent());

        if ($response->getStatusCode() == Response::HTTP_OK) {

            $mpesa = new Mpesa();

        }*/
        $url = env('MPESA_C2B_CONFIRM');
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_SSLKEY, Storage::disk('mpesa')->get('tkj.vodacom.co.tz.key'));
        curl_setopt($ch, CURLOPT_CAINFO, Storage::disk('mpesa')->get('root.pem'));
        curl_setopt($ch, CURLOPT_SSLCERT, Storage::disk('mpesa')->get('tkj.vodacom.co.tz.cer'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->getBodyContent() );
        curl_exec($ch);
        // Check HTTP status code
        if (!curl_errno($ch)) {
            switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
                case 200:
                    //Confirm the transaction, generate ticket and send notification to user

                    break;
                default:
                    echo 'Unexpected HTTP code: ', $http_code, "\n";
            }
        }
        curl_close($ch);
    }

    private function getBodyContent(){
        return $this->c2bPaymentConfirmRequest([

        ]);
    }
}
