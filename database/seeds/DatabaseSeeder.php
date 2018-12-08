<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use \App\Services\Tickets\TicketVerification;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(MerchantsSeederTable::class);
        //$this->call(BusTypesSeederTable::class);
        //$this->call(RoutesSeederTable::class);
        //$this->call(BusesSeederTable::class);
        //$this->call(AssignBusRouteSeederTable::class);
        //$this->call(AssignScheduleSeederTable::class);
        //$this->call(BookingsSeederTable::class);
        //$this->call(MerchantPaymentSeederTable::class);

        /*$bus = \App\Models\Bus::with(['merchant','merchant.paymentAccounts:merchant_id,payment_mode,account_number'])->select('buses.merchant_id')->find(1);

        $payOptions = ['Select payment method'];
        foreach ($bus->merchant->paymentAccounts as $account){
            $payOptions[$account->payment_mode] = $this->getPaymentModeDisplayLabel($account->payment_mode);
        }

        print json_encode($payOptions).PHP_EOL;*/
       /* $condition[] = ['created_at', '<', \Carbon\Carbon::now()->subMinutes(5)->toDateTimeString()];
        $condition[] = ['status', '=',\App\Models\Booking::STATUS_PENDING];
        $bookings = \App\Models\Booking::where($condition)->get();

        $i = 0;
        $j = 0;

        foreach ($bookings as $booking){
            $ticket =$booking->ticket;

            if(isset($ticket)){
                $j++;
                print $j.' booking has ticket'.PHP_EOL;
                print '==========='.PHP_EOL;
                continue;
            }

            $this->deleteFailedBooking($booking);
            $i++;
            print $i.' booking deleted'.PHP_EOL;
            print '==========='.PHP_EOL;
        }*/
        //print json_encode($bookings).PHP_EOL;
        /*$response = '<mpesaBroker version ="2.0" xmlns="http://infowise.co.tz/broker/"><response><conversationID>1645ba1ae67b43f5badbe3a70df84885</conversationID><originatorConversationID>1645ba1ae67b43f5badbe3a70df84885</originatorConversationID><responseCode>0</responseCode><serviceStatus>Confirming</serviceStatus><transactionID>173388288_664400</transactionID></response></mpesaBroker>';
        $object = simplexml_load_string($response);
        $json =json_encode($object);
        print $json.PHP_EOL;*/

        $response = $this->verifyTicketByReference('FC08NLUQVA8G');

        print json_encode($response).PHP_EOL;
    }
}
