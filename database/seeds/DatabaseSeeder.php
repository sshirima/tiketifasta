<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use \App\Services\Bookings\AuthorizeBooking;
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
        $condition[] = ['created_at', '<', \Carbon\Carbon::now()->subMinutes(5)->toDateTimeString()];
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
        }
        //print json_encode($bookings).PHP_EOL;

    }
}
