<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/10/2018
 * Time: 10:47 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Http\Requests\api\CreateBookingPayment;
use App\Models\BookingPayment;
use App\Services\Payments\PaymentManager;

class BookingPaymentController extends Controller
{

    private $paymentManager;

    public function __construct(PaymentManager $paymentManager)
    {
        $this->middleware('auth:api')->except('index','show');
        $this->paymentManager = $paymentManager;
    }

    public function index(){
        return BookingPayment::all();
    }

    public function store(CreateBookingPayment $request){
        $input = $request->all();

        $input['method'] = 'mpesa';

        $bookingPayment = $this->paymentManager->addBookingPayment($input);
        if (isset($bookingPayment)){
            return 'created';
        }else {
            return 'failed';
        }

    }
}