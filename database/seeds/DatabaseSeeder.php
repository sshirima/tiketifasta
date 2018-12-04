<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
        $this->call(AssignScheduleSeederTable::class);
        //$this->call(BookingsSeederTable::class);
        //$this->call(MerchantPaymentSeederTable::class);

        /*$bus = \App\Models\Bus::with(['merchant','merchant.paymentAccounts:merchant_id,payment_mode,account_number'])->select('buses.merchant_id')->find(1);

        $payOptions = ['Select payment method'];
        foreach ($bus->merchant->paymentAccounts as $account){
            $payOptions[$account->payment_mode] = $this->getPaymentModeDisplayLabel($account->payment_mode);
        }

        print json_encode($payOptions).PHP_EOL;*/

    }

    private function getPaymentModeDisplayLabel($paymentMode){
        if($paymentMode == 'tigopesa'){
            return 'Tigo pesa';
        }

        if($paymentMode == 'mpesa'){
            return 'M-pesa';
        }

        if($paymentMode == 'airtel'){
            return 'Airtel money';
        }

        return 'Unknown';
    }
}
