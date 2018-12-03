<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/15/2018
 * Time: 2:16 PM
 */

namespace App\Services\Schedules;

use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Ticket;

trait SchedulesAuthorization
{

    /**
     * @param Schedule $schedule
     * @param $status
     * @param $updateDependencies
     */
    public function deactivateSchedule(Schedule $schedule, $status, $updateDependencies=1){

        $this->updateScheduleStatus($schedule, $status);

        if($updateDependencies){
            $this->updateScheduleDependencies($schedule);
        }
    }

    /**
     * @param Schedule $schedule
     */
    public function updateScheduleDependencies(Schedule $schedule){
        //Update bookings
        $bookings = $schedule->bookings()->get();

        foreach ($bookings as $booking){
            $this->setBookingExpired($booking);

            $ticket = $booking->ticket;

            $this->setTicketExpired($ticket);
        }
    }

    /**
     * @param Booking $booking
     */
    private function setBookingExpired(Booking $booking){
        $booking->status = Booking::STATUS_EXPIRED;
        $booking->update();
    }

    /**
     * @param Ticket $ticket
     */
    private function setTicketExpired(Ticket $ticket){
        $ticket->status = Ticket::STATUS_EXPIRED;
        $ticket->update();
    }

    /**
     * @param Schedule $schedule
     * @param $status
     */
    public function updateScheduleStatus(Schedule $schedule, $status){
        $schedule->status = $status;
        $schedule->update();
    }
}