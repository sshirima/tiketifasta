<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Services\Payments\PaymentManager;
use App\Models\TigoOnlineC2B;
use App\Models\Ticket;
use App\Models\BookingPayment;
use App\Models\MpesaC2B;

class BookingsSeederTable extends Seeder
{

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

        $schedules = \Illuminate\Support\Facades\DB::table('schedules')
            ->select('schedules.id as schedule_id','buses.id as bus_id','buses.reg_number as reg_number','days.date as date',
                'trips.id as trip_id','trips.price as price','trips.direction as direction')
            ->join('buses','buses.id','=','schedules.bus_id')
            ->join('days','days.id','=','schedules.day_id')
            ->join('trips',function ($join){
                $join->on('trips.bus_id','=','buses.id')->on('trips.direction','=','schedules.direction');
            })
            ->whereDate('days.date','=','2018-12-03')
            ->get();
        print 'INFO: Fetch schedules, Schedules: ('.count($schedules).')'.PHP_EOL;
        print '====================='.PHP_EOL;

        foreach ($schedules as $schedule){
            $seat = \App\Models\Seat::select('seats.id as seat_id','seats.seat_name as seat_name')->where(['bus_id'=>$schedule->bus_id])->get()->shuffle()->first();
            print 'INFO: Fetch selected seat'.PHP_EOL;


            //Select payments accounts
            $accounts = $this->getMerchantAccountsBySchedule($schedule);
            $selectedAccount =$this->faker->randomElement($accounts);

            //Create bookings
            $booking = $this->createBooking($schedule, $seat , $selectedAccount->payment_mode);
            print 'INFO: Create booking model'.PHP_EOL;

            //Create bookingPayment model
            $bookingPayment = $this->createBookingPaymentTransaction($booking);
            print 'INFO: Create booking payment model'.PHP_EOL;

            //Create schedule_seat model
            $this->createScheduleSeat($schedule, $seat);
            print 'INFO: Create schedule_seat'.PHP_EOL;

            //if Mpesa
            if($bookingPayment->method == 'mpesa'){
                //create MpesaC2b model
                $mpesaC2B = $this->createMpesaC2BTransaction($booking, $bookingPayment);
                print 'INFO: Create MpesaC2B transaction'.PHP_EOL;

                //Update mpesa validation parameters
                $this->updateValidationParameters($mpesaC2B);
                print 'INFO: Update MpesaC2B validation parameters'.PHP_EOL;

                //Generate service number
                $this->generateServiceNumber($mpesaC2B);
                print 'INFO: Generate MpesaC2B service number'.PHP_EOL;

                //Confirm MpesaC2B transaction model
                $this->setMpesaC2BStatusConfirmed($mpesaC2B);
                print 'INFO: Set MpesaC2B status as confirmed'.PHP_EOL;
            }

            //If Tigopesa
            if($bookingPayment->method == 'tigopesa'){
                //create tigoC2B model
                $tigoC2B = $this->createTigoC2BTransaction($booking, $bookingPayment);
                print 'INFO: Create TigoC2B transaction'.PHP_EOL;

                //Update model authorization
                $this->setTigoB2CAuthorization($tigoC2B);
                print 'INFO: Set TigoC2B authorization parameters'.PHP_EOL;

                //Confirm TigoC2B transaction
                $this->confirmTigoC2BTransaction($tigoC2B);
                print 'INFO: Confirm TigoC2B transaction'.PHP_EOL;
            }


            //Authorize booking
            $this->authorizeBooking($booking);
            print 'INFO: Authorize booking'.PHP_EOL;

            //Create ticket
            $ticket = $this->createTicket($bookingPayment);
            print 'INFO: Create ticket'.PHP_EOL;

            //Confirm booking
            $booking->confirmBooking();
            print 'INFO: Confirm booking'.PHP_EOL;

            //Confirm ticket
            $this->confirmTicket($ticket);
            print 'INFO: Confirm ticket'.PHP_EOL;

            break;
        }
    }

    protected function getMerchantAccountsBySchedule($schedule){
        return $merchants = \App\Models\Schedule::select('merchant_payment_accounts.payment_mode','merchant_payment_accounts.account_number')
            ->join('buses','buses.id','=','schedules.bus_id')
            ->join('merchants','merchants.id','=','buses.merchant_id')
            ->join('merchant_payment_accounts','merchants.id','=','merchant_payment_accounts.merchant_id')
            ->where('schedules.id','=',$schedule->schedule_id)->get();
    }

    /**
     * @param TigoOnlineC2B $tigoOnlineC2B
     */
    public function confirmTigoC2BTransaction(TigoOnlineC2B $tigoOnlineC2B){

        $tigoOnlineC2B->status = TigoOnlineC2B::STATUS_SUCCESS;

        $tigoOnlineC2B->update();
    }

    /**
     * @param Ticket $ticket
     * @return bool
     */
    public function confirmTicket(Ticket $ticket){
        $ticket->status = Ticket::STATUS_CONFIRMED;
        return $ticket->update();
    }

    /**
     * @param MpesaC2B $mpesaC2B
     */
    public function setMpesaC2BStatusConfirmed(MpesaC2B $mpesaC2B)
    {
        //Stage 0
        $mpesaC2B->stage ='0';
        $mpesaC2B->service_status = 'confirmed';
        $mpesaC2B->update();
    }

    /**
     * @param BookingPayment $bookingPayment
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function createTicket(BookingPayment $bookingPayment){

        $booking = $bookingPayment->booking()->first();

        $ticket = $bookingPayment->ticket()->first();

        if (isset($ticket)){
            return $ticket;
        } else {
            return Ticket::create([
                Ticket::COLUMN_TICKET_REFERENCE => strtoupper(PaymentManager::random_code(6)),
                Ticket::COLUMN_BOOKING_ID => $booking->id,
                Ticket::COLUMN_PAYMENT_ID => $bookingPayment->id,
                Ticket::COLUMN_PRICE => $bookingPayment->price,
                Ticket::COLUMN_PRICE => $bookingPayment->amount,
                Ticket::COLUMN_STATUS => Ticket::STATUS_VALID,
            ]);
        }
    }

    /**
     * @param \App\Models\Booking $booking
     */
    public function authorizeBooking(\App\Models\Booking $booking){
        $booking->status = 'AUTHORIZED';
        $booking->update();

    }

    public function createBooking($schedule, $seat, $operator){

        return \App\Models\Booking::create([
            'title'=>$this->faker->title,
            'firstname'=>$this->faker->firstName,
            'lastname'=>$this->faker->lastName,
            'phonenumber'=>'0'.random_int(6,7).random_int(1,7).random_int(1,9).random_int(100000,999999),
            'payment'=>$operator,
            'email'=>$this->faker->safeEmail,
            'seat_id'=>$seat->seat_id,
            'price'=>$schedule->price,
            'trip_id'=>$schedule->trip_id,
            'status'=>'PENDING',
            'schedule_id'=>$schedule->schedule_id,
        ]);
    }

    public function createScheduleSeat($schedule, $seat){

        return \App\Models\ScheduleSeat::create([
            'seat_id'=>$seat->seat_id,
            'schedule_id'=>$schedule->schedule_id,
            'status'=>'Booked',
        ]);
    }

    private function createBookingPaymentTransaction($booking){
        return \App\Models\BookingPayment::create([
            'payment_ref'=>strtoupper(PaymentManager::random_code(12)),
            'amount'=>$booking->price,
            'booking_id'=>$booking->id,
            'method'=>$booking->payment,
            'phone_number'=>$booking->phonenumber,
        ]);
    }

    private function createMpesaC2BTransaction($booking, $bookingPayment){
        return \App\Models\MpesaC2B::create([
            'msisdn'=>$booking->phonenumber,
            'amount'=>$booking->price,
            'account_reference'=>$bookingPayment->payment_ref,
            'booking_payment_id'=>$bookingPayment->id,
        ]);
    }


    private function createTigoC2BTransaction($booking, $bookingPayment){
        return \App\Models\TigoOnlineC2B::create([
            TigoOnlineC2B::COLUMN_REFERENCE => strtoupper(PaymentManager::random_code(12)),
            TigoOnlineC2B::COLUMN_PHONE_NUMBER => $booking->phonenumber,
            TigoOnlineC2B::COLUMN_FIRST_NAME =>$booking->firstname,
            TigoOnlineC2B::COLUMN_LAST_NAME => $booking->lastname,
            TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID => $bookingPayment->id,
            TigoOnlineC2B::COLUMN_TAX =>'0',
            TigoOnlineC2B::COLUMN_FEE => '0',
            TigoOnlineC2B::COLUMN_AMOUNT => $booking->price,
        ]);
    }

    /**
     * @param $tigoC2B
     */
    private function setTigoB2CAuthorization($tigoC2B){
        $tigoC2B->auth_code = PaymentManager::random_code(10);
        $tigoC2B->authorized_at = date('Y-m-d H:i:s');
        $tigoC2B->update();
    }


    protected function updateValidationParameters($mpesaC2B): void
    {
        $receipt =strtoupper(PaymentManager::random_code(11));

        $mpesaC2B->command_id = 'Pay Bill';
        $mpesaC2B->og_conversation_id = $this->faker->uuid;
        $mpesaC2B->recipient = '278787';
        $mpesaC2B->mpesa_receipt = $receipt;
        $mpesaC2B->transaction_date = date('Y-m-d H:i:s');
        $mpesaC2B->transaction_id = '144'.random_int(100000,999999).'420407'.'_278787';
        $mpesaC2B->conversation_id = $receipt;
        $mpesaC2B->authorized_at = date('Y-m-d H:i:s');
        $mpesaC2B->stage = '2';
        $mpesaC2B->update();
    }

    /**
     * @param \App\Models\MpesaC2B $mpesaC2B
     * @return bool
     */
    public function generateServiceNumber(\App\Models\MpesaC2B $mpesaC2B)
    {
        //Generate service number
        $mpesaC2B->service_receipt = strtoupper(PaymentManager::random_code(8));
        $mpesaC2B->stage = '1';

        return $mpesaC2B->update();
    }
}
