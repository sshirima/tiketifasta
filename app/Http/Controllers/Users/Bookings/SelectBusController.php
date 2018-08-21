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
use App\Jobs\ConfirmBookingPayment;
use App\Models\Booking;
use App\Models\ScheduleSeat;
use App\Models\Seat;
use App\Models\Trip;
use App\Services\Bookings\BookingManager;
use App\Services\DateTime\DatesOperations;
use App\Services\DateTimeService;
use App\Services\Payments\PaymentManager;
use App\Services\Trips\TripsManager;
use Illuminate\Http\Request;

class SelectBusController extends Controller
{
    use DatesOperations;

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

        if ($request->has('date')){
            $dateIsValid = $this->compareTime($input['date'], date('Y-m-d'));
            $date = DateTimeService::convertDate($input['date'], 'Y-m-d');
        } else {
            $dateIsValid = 1;
            $input['date'] = date('Y-m-d');
            $date = null;
        }


        if($dateIsValid){
            $trips = $this->tripsManager->findTripForBookings($date, $input['from'], $input['to']);
            return view('users.pages.bookings.select_bus')->with(['trips' => $trips]);
        } else{
            return view('users.pages.bookings.select_bus')->with(['date_error'=>1]);
        }
       /*return view('users.pages.bookings.select_bus')->with(['trips' => $trips]);*/
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

        return view('users.pages.bookings.booking_details')->with(['trip' => $trip]);
    }

    /**
     * @param CreateBookingRequest $request
     * @param $busId
     * @param $scheduleId
     * @param $tripId
     * @return array
     */
    public function bookingStore(CreateBookingRequest $request, $busId, $scheduleId, $tripId)
    {
        $booking = null;
        $error = null;
        $bookingPayment = null;
        $trip = null;

        //Confirm
        try
        {
            $booking = $this->bookingManager->bookTicket($request->all(), $busId, $scheduleId, $tripId);

            $trip = $this->tripsManager->getSelectedTripDetails($scheduleId, $tripId, $request->all()['seat']);

            $bookingPayment = $booking->bookingPayment;
        }
        catch (\Exception $exception)
        {
            $error = $exception->getMessage();
        }

        //Present the response to the user for payments
        return view('users.pages.bookings.booking_confirmation')->with(['error'=>$error,'bookingPayment'=>$bookingPayment,'booking' => $booking, 'trip' => $trip]);
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

}