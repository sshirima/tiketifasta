<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/27/2018
 * Time: 10:45 PM
 */

namespace App\Http\Controllers\Users;


use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateBookingRequest;
use App\Http\Requests\Users\VerifyTicketRequest;
use App\Models\BookingPayment;
use App\Models\Bus;
use App\Models\Seat;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Bookings\BookingManager;
use App\Services\DateTimeService;
use App\Services\Payments\BookingPayments\BookingPaymentProcessor;
use App\Services\Payments\PaymentManager;
use App\Services\Tickets\TicketVerification;
use App\Services\Trips\TripsManager;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Log;

class VerifyTicketController extends Controller
{
    use TicketVerification;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayForm()
    {
        return view('users.pages.ticket_verification');
    }

    /**
     * @param VerifyTicketRequest $request
     *
     * @return $this
     */
    public function verifyReference(VerifyTicketRequest $request)
    {

        $reference = $request->input('reference');

        $toUpperCase = strtoupper($reference);

        $response = $this->verifyTicketByReference($toUpperCase);

        if(!$response['status']){
            return view('users.pages.ticket_verification')->with(['error'=>'We could not retrieve any ticket with the given records']);
        }

        $booking = $response['booking'];
        return view('users.pages.ticket_verification')->with(['booking'=>$booking,'ticket'=>$booking->ticket,'trip'=>$booking->trip]);
    }
}