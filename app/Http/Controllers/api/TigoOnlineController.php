<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/10/2018
 * Time: 10:47 AM
 */

namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\TigoOnlineC2B;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\DateTime\DatesOperations;
use App\Services\Payments\BookingPayments\BookingPaymentProcessor;
use App\Services\Payments\PaymentManager;
use App\Services\Payments\Tigosecure\TigoOnline;
use App\Services\SMS\SendSMS;
use App\Services\Tickets\TicketManager;
use Illuminate\Http\Request;
use Exception;
use App\Services\SMS\Smpp;
use Log;

class TigoOnlineController extends Controller
{
    use DatesOperations, TicketManager, AuthorizeBooking, BookingPaymentProcessor;

    private $tigoOnline;

    public function __construct(TigoOnline $tigoOnline)
    {
        $this->tigoOnline = $tigoOnline;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function confirmPayment(Request $request)
    {
        $response = $this->tigoOnline->confirmTigoSecureC2BTransaction($request);

        if(!$response['status']){

            $this->deleteBooking($response);

            return view('users.pages.bookings.booking_confirmation')->with(['error' => $response['error']]);
        }

        $tigoC2B = $response['model'];
        $bookingPayment = $tigoC2B->bookingPayment;

        $this->setBookingConfirmed($bookingPayment->booking);

        $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_SETTLED);

        $ticket = $this->processTicket($tigoC2B);

        $this->confirmTicket($tigoC2B, $bookingPayment);

        return view('users.pages.bookings.booking_confirmation')->with(['ticket' => $ticket, 'transaction' => $tigoC2B,
            'bookingPayment' => $tigoC2B->bookingPayment, 'booking' => $ticket->booking]);
    }

    /**
     * @param $response
     */
    protected function deleteBooking($response): void
    {
        if (array_key_exists('model', $response)) {

            $tigoC2B = $response['model'];

            $this->deleteBookingByTransaction($tigoC2B);

        }
    }

}