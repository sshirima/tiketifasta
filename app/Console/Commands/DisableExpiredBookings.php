<?php

namespace App\Console\Commands;

use App\Models\Day;
use App\Services\Bookings\AuthorizeBooking;
use Illuminate\Console\Command;

class DisableExpiredBookings extends Command
{
    use AuthorizeBooking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable expired bookings';

    /**
     * DisableExpiredBookings constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info('Running scheduled command: '.$this->signature);

        $days = Day::with(['bookings'])->whereDate('date', '<', date('Y-m-d'))->get();

        foreach ($days as $day) {
            foreach ($day->bookings as $booking){
                $this->setBookingExpired($booking);
            }
        }
    }
}
