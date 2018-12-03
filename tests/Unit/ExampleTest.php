<?php

namespace Tests\Unit;

use App\Models\Merchant;
use App\Services\Merchants\MerchantAuthorization;
use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use MerchantPaymentProcessor, MerchantAuthorization;

    /**
     * A basic test example.
     *
     * @return void
     */
    /*public function testCalculateCommission()
    {
        //Given
        $actual = 1000;

        //When
        $commission = $this->calculateCommissionedAmount($actual);

        //Result
        $this->assertEquals(100, $commission);

        $this->assertEquals(900, $this->calculateMerchantAmount($actual, $commission));
    }*/

    /*public function testMerchantAuthorization(){

        $merchant = Merchant::find(5);

        $this->assertTrue($this->authorizeMerchant($merchant,1,1),'Merchant account authorized');
    }*/
}
