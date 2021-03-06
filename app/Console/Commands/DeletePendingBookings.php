<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\Bookings\AuthorizeBooking;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeletePendingBookings extends Command
{
    use AuthorizeBooking;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:delete-pendings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending bookings which lasted for more than specified time';


    /**
     * DisableExpiredSchedules constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Disable expired schedules
     */
    public function handle()
    {
        Log::info('Running scheduled command: '.$this->signature);

        try{
            $condition[] = ['created_at', '<', Carbon::now()->subMinutes(5)->toDateTimeString()];
            $condition[] = ['status', '=',Booking::STATUS_PENDING];

            $bookings = Booking::where($condition)->get();

            foreach ($bookings as $booking){
                $ticket =$booking->ticket;
                if(isset($ticket)){
                    continue;
                }
                $this->deleteFailedBooking($booking);
                Log::info('Delete pending booking, success, id:'.$bookings->id);
            }
        }catch(\Exception $ex){
            Log::error('Delete pending booking, failed, exception: '.$ex->getMessage() );
        }

        //$this->info('');
    }
}
