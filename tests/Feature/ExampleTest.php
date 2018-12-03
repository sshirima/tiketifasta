<?php

namespace Tests\Feature;

use App\Services\Payments\MerchantPayments\MerchantPaymentProcessor;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use MerchantPaymentProcessor;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        //Given
        $actual = 1000;

        //When
        $commission = $this->calculateCommissionedAmount($actual);

        //Result
        $this->assertEquals(100, $commission);
    }
}
