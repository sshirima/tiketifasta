<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MerchantsSeederTable extends Seeder
{

    protected $faker;

    public function __construct(Faker $faker)
    {
        $this->faker = $faker;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*$startDate = \Illuminate\Support\Carbon::createFromTimeStamp($this->faker->dateTimeBetween('-30 days', '+30 days')->getTimestamp());
        $endDate = \Illuminate\Support\Carbon::createFromFormat('Y-m-d H:i:s', $startDate)->addYear(1);

        print 'Start date: '.$startDate->format('Y-m-d').PHP_EOL;
        print 'End date: '.$endDate->format('Y-m-d');
        $operator = $this->faker->randomElement($array = array('mpesa','tigopesa'));
        print 'Operator: '.$operator.PHP_EOL;*/

        factory(\App\Models\Merchant::class, 10)->create()->each(function ($merchant) {
            //Create merchant
            $merchant->save();
            print 'INFO: Merchant account created'.PHP_EOL;

            //Create default account
            $this->createCompanyAccount($merchant);
            print 'INFO: Default staff account for merchant created'.PHP_EOL;

            //Create merchant payment account
            $this->createMerchantPaymentAccount($merchant);
            print 'INFO: Merchant payment account created'.PHP_EOL;
            print '======================================'.PHP_EOL;


        });
    }

    /**
     * @param $merchant
     * @return mixed
     */
    public function createCompanyAccount($merchant){

        return \App\Models\Staff::create([
            'firstname'=>$this->faker->firstName,
            'lastname'=>$this->faker->lastName,
            'phonenumber'=>'0'.random_int(6,7).random_int(1,7).random_int(1,9).random_int(100000,999999),
            'password'=>bcrypt('password'),
            'email'=>$this->faker->companyEmail,
            'merchant_id'=>$merchant->id,
        ]);
    }

    /**
     * @param $merchant
     * @return mixed
     */
    public function createMerchantPaymentAccount($merchant){

        $operator = $this->faker->randomElement($array = array('mpesa','tigopesa'));

        return \App\Models\MerchantPaymentAccount::create([
            'account_number'=>'0'.random_int(6,7).random_int(1,7).random_int(1,9).random_int(100000,999999),
            'payment_mode'=>$operator,
            'merchant_id'=>$merchant->id,
        ]);
    }
}
