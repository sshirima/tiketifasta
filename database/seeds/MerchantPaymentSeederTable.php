<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class MerchantPaymentSeederTable extends Seeder
{

    use \App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
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
        $response = $this->processPayments(date('Y-m-d'));

        print 'INFO: Task status: '.json_encode($response).PHP_EOL;
        print '=================================='.PHP_EOL;
    }
}
