<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\ScheduleSeat;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ConfirmBookingPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $scheduleSeat;


    public function __construct(Booking $booking, ScheduleSeat $scheduleSeat)
    {
        $this->booking = $booking;
        $this->scheduleSeat = $scheduleSeat;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->booking->status == 'PENDING'){
            $this->scheduleSeat->delete();
            $this->booking->delete();
        }
    }
}
