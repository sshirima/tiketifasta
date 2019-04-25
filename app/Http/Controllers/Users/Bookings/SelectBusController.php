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
use App\Models\BookingPayment;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\Seat;
use App\Services\Bookings\AuthorizeBooking;
use App\Services\Bookings\BookingManager;
use App\Services\DateTimeService;
use App\Services\Payments\BookingPayments\BookingPaymentProcessor;
use App\Services\Payments\PaymentManager;
use App\Services\Trips\TripsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Laracasts\Flash\Flash;
use Log;

class SelectBusController extends Controller
{
    use BookingPaymentProcessor, AuthorizeBooking;

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

            $from = array_key_exists('from', $input)?$input['from']:'';
            $to = array_key_exists('to', $input)?$input['to']:'';

            $trips = $this->tripsManager->findTripForBookings($date, $from, $to);

            return view('users.pages.bookings.select_bus')->with(['trips' => $trips, 'input'=>$input]);

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

        return view('users.pages.bookings.select_seats')->with(['trip' => $trip, 'seats' => $seats,'schedule'=>Schedule::find($scheduleId)]);
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
        $seat = $request->all()['seat'];

        $trip = $this->tripsManager->getSelectedTripDetails($scheduleId, $tripId, $seat);


        $status = $this->bookingManager->reserveSeat($seat, $busId, $scheduleId);

        if(!$status['status']){
            return redirect()->back()->withErrors($status['error']);
        }

        $payOptions = $this->getPaymentOptions($busId);

        return view('users.pages.bookings.booking_details')->with(['trip' => $trip,
            'boarding_points'=>$this->tripsManager->getStations($trip->from_id),
            'dropping_points'=>$this->tripsManager->getStations($trip->to_id),
            'paymentOptions'=>$payOptions]);
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

            $seat = Seat::select(['*'])->where([Seat::COLUMN_BUS_ID => $busId, Seat::COLUMN_SEAT_NAME => $input['seat']])->first();
            $res = $this->bookingManager->processNewBooking($input, $seat, $scheduleId, $tripId);

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
                //Set bookingPayment as authorized
                $this->changeBookingPaymentTransactionStatus($bookingPayment, BookingPayment::TRANS_STATUS_AUTHORIZED);
                return redirect($bookingPaymentResponse['redirectUrl']);//($bookingPayment['redirectUrl']);
            }

            $error = 'Something went wrong on the request, please contact the support team';
            return redirect()->back()->with(['error'=>$error]);

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

    /**
     * @param Request $request
     */
    private function checkLanguage(Request $request){
        if($request->has('locale')){
            $locale = $request->get('locale');
            App::setLocale($locale);
        }
    }

}