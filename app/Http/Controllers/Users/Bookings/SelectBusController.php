<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/27/2018
 * Time: 10:45 PM
 */

namespace App\Http\Controllers\Users\Bookings;


use App\Http\Controllers\Controller;
use App\Http\Requests\Users\CreateBookingRequest;
use App\Models\Bus;
use App\Models\Seat;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Bookings\BookingManager;
use App\Services\DateTimeService;
use App\Services\Payments\BookingPayments\BookingPaymentProcessor;
use App\Services\Payments\PaymentManager;
use App\Services\Trips\TripsManager;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Log;

class SelectBusController extends Controller
{
    use BookingPaymentProcessor, AuthorizeBooking;

    private $selectedSeat;

    private $tripsManager;
    private $bookingManager;
    private $paymentManager;

    public function __construct(TripsManager $tripsManager, BookingManager $bookingManager, PaymentManager $paymentManager)
    {
        $this->tripsManager = $tripsManager;
        $this->bookingManager = $bookingManager;
        $this->paymentManager = $paymentManager;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function search(Request $request)
    {
        $input = $request->all();

        if ($request->has('date')) {

            $dateIsValid = $this->compareTime($input['date'], date('Y-m-d'));

            $date = DateTimeService::convertDate($input['date'], 'Y-m-d');

        } else {
            $dateIsValid = 1;
            $input['date'] = date('Y-m-d');
            $date = null;
        }


        if ($dateIsValid) {

            $trips = $this->tripsManager->findTripForBookings($date, $input['from'], $input['to']);

            return view('users.pages.bookings.select_bus')->with(['trips' => $trips]);

        } else {
            return view('users.pages.bookings.select_bus')->with(['date_error' => 1]);
        }
    }

    /**
     * @param Request $request
     * @param $busId
     * @param $scheduleId
     * @param $tripId
     * @return $this
     */
    public function selectBus(Request $request, $busId, $scheduleId, $tripId)
    {

        $trip = $this->tripsManager->selectTripForBooking($tripId, $scheduleId);

        $seats = $this->getBusSeats(Seat::seatArrangementArray($trip->bus->busType->seat_arrangement), $trip->bus->bookedSeats);

        return view('users.pages.bookings.select_seats')->with(['trip' => $trip, 'seats' => $seats]);
    }

    /**
     * @param Request $request
     * @param $busId
     * @param $scheduleId
     * @param $tripId
     * @return $this
     */
    public function selectSeat(Request $request, $busId, $scheduleId, $tripId)
    {
        $trip = $this->tripsManager->getSelectedTripDetails($scheduleId, $tripId, $request->all()['seat']);

        $payOptions = $this->getPaymentOptions($busId);

        return view('users.pages.bookings.booking_details')->with(['trip' => $trip,'paymentOptions'=>$payOptions]);
    }

    /**
     * @param CreateBookingRequest $request
     * @param $busId
     * @param $scheduleId
     * @param $tripId
     * @return $this|string
     */
    public function bookingStore(CreateBookingRequest $request, $busId, $scheduleId, $tripId)
    {
        try {
            $input = $request->all();

            $res = $this->bookingManager->processNewBooking($input, $busId, $scheduleId, $tripId);

            if(!$res['status']){
                $this->cancelBooking($res['error'], null);
                return redirect()->back()->with(['error'=>$res['error']]);
            }

            $booking = $res['booking'];

            $bookingPaymentResponse = $this->processNewBookingPayment($booking);

            if(!$bookingPaymentResponse['status']){
                $this->cancelBooking($bookingPaymentResponse['error'], $booking);
                return redirect()->back()->with(['error'=>$bookingPaymentResponse['error']]);
            }

            $bookingPayment = $bookingPaymentResponse['bookingPayment'];

            if($bookingPayment->method == 'mpesa'){
                if (array_key_exists('error',$bookingPaymentResponse)){
                    $this->cancelBooking($bookingPaymentResponse['error'], $booking);
                    return redirect()->back()->with(['error'=>$bookingPaymentResponse['error']]);
                }

                $trip = $this->tripsManager->getSelectedTripDetails($scheduleId, $tripId, $request->all()['seat']);
                $bookingPayment = $booking->bookingPayment;
                return view('users.pages.bookings.booking_confirmation')->with(['bookingPayment' => $bookingPayment, 'booking' => $booking, 'trip' => $trip]);
            }

            if($bookingPayment->method == 'tigopesa'){

                if (array_key_exists('error',$bookingPaymentResponse)){
                    $this->cancelBooking($bookingPaymentResponse['error'], $booking);
                    return redirect()->back()->with(['error'=>$bookingPaymentResponse['error']]);
                }
                dd($bookingPayment['redirectUrl']);
                return redirect()->to($bookingPayment['redirectUrl']);//($bookingPayment['redirectUrl']);
            }
            $error = 'Something went wrong on the request, please contact the support team';
            return redirect()->back()->with(['error'=>$error]);

            /*if (isset($res['booking']) && isset($res['paymentModel'])){

                if ($booking->payment == 'tigopesa') {

                    $tigoOnlineC2B = $res['paymentModel'];

                    $response = $this->authorizeTigoC2BTransaction($tigoOnlineC2B);

                    if ($response['status_code'] == 200) {

                        $res = json_decode($response['response']);

                        if (isset($res->authCode)) {
                            $tigoOnlineC2B->auth_code = $res->authCode;
                            $tigoOnlineC2B->authorized_at = $this->convertDate($tigoOnlineC2B->creationDateTime,'Y-m-d H:i:s');
                            $tigoOnlineC2B->update();
                            return redirect($res->redirectUrl);
                        } else {
                            //Log auth code not found
                            $tigoOnlineC2B->delete();
                            $bookingPayment = $booking->bookingPayment;
                            $bookingPayment->delete();
                            $booking->delete();
                            Log::channel('tigosecurec2b')->error('Auth code not found[' . 'Response:' . $response['response'] . ']' . PHP_EOL);
                            $error = 'Tigo pesa payment authorization failed';
                            return view('users.pages.bookings.booking_confirmation')->with(['error' => $error, 'bookingPayment' => $bookingPayment, 'booking' => null, 'trip' => $trip]);
                        }

                    } else {
                        //Log Payment authorization failed
                        Log::channel('tigosecurec2b')->error('Authorization failed[' . 'StatusCode:' . $response['status_code'] . ']' . PHP_EOL);
                        $error = 'Tigo pesa payment authorization failed';
                        return view('users.pages.bookings.booking_confirmation')->with(['error' => $error, 'bookingPayment' => $bookingPayment, 'booking' => null, 'trip' => $trip]);
                    }
                }
            } else {
                return view('users.pages.bookings.booking_confirmation')->with(['error' => 'Failed to create bookings, please retry','trip' => $trip]);
            }*/

        } catch (\Exception  $exception) {
            $error = $exception->getMessage();
            $this->cancelBooking($exception->getMessage(), null);
            return redirect()->back()->with(['error'=>$error]);
        }
    }

    private function cancelBooking($error, $booking){

        if(isset($booking)){
            $this->deleteFailedBooking($booking);
        }

        Log::error($error);
    }

    /**
     * @param $busSeats
     * @param $bookedSeats
     * @return array
     */
    private function getBusSeats($busSeats, $bookedSeats)
    {

        //Mark booked seats
        foreach ($bookedSeats as $bookedSeat) {
            $busSeats[$bookedSeat->seat->seat_name]['status'] = 'unavailable';
        }

        //Marked available seats
        foreach ($busSeats as $key => $busSeat) {
            if (!array_key_exists('status', $busSeat)) {
                $busSeats[$key]['status'] = 'Available';
            }
        }

        //Arrange to get a seatArray
        $array = array();

        foreach ($busSeats as $key => $seat) {
            $seat['name'] = $key;
            $array[$busSeats[$key]['index']] = $seat;
        }
        return $array;
    }

    /**
     * @param $paymentMode
     * @return string
     */
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

    /**
     * @param $busId
     * @return array
     */
    protected function getPaymentOptions($busId): array
    {
        $bus = Bus::with(['merchant', 'merchant.paymentAccounts:merchant_id,payment_mode,account_number'])->select('buses.merchant_id')->find($busId);

        $payOptions = ['Select payment method'];
        foreach ($bus->merchant->paymentAccounts as $account) {
            $payOptions[$account->payment_mode] = $this->getPaymentModeDisplayLabel($account->payment_mode);
        }
        return $payOptions;
    }

}