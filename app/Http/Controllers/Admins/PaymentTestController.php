<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 4/5/2018
 * Time: 10:30 PM
 */

namespace App\Http\Controllers\Admins;

use App\Services\Payments\PaymentManager;
use Symfony\Component\HttpFoundation\Response;

class PaymentTestController extends BaseController
{

    private  $paymentManager;
    private $accessToken;

    /**
     * PaymentTestController constructor.
     * @param PaymentManager $paymentManager
     */
    public function __construct(PaymentManager $paymentManager)
    {
        parent::__construct();
        $this->paymentManager = $paymentManager;
    }
}