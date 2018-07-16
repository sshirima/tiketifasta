<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/8/2018
 * Time: 4:04 PM
 */

namespace App\Services\Trips;


use App\Models\Trip;

trait SearchTripsService
{
    protected $scheduleId;
    protected $tripId;

    /**
     * @param $date
     * @param $from
     * @param $to
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findTripForBookings($date, $from, $to)
    {
        return Trip::with([
            'bus', 'bus.merchant'
        ])->select(['trips.id as trip_id', 'A.name as from', 'trips.price', 'B.name as to', 'trips.bus_id', 'days.date', 'trips.arrival_time', 'trips.depart_time', 'schedules.id as schedule_id'])
            ->join('locations as A', 'A.id', '=', 'source')
            ->join('locations as B', 'B.id', '=', 'destination')
            ->join('schedules', 'schedules.bus_id', '=', 'trips.bus_id')
            ->join('days', 'schedules.day_id', '=', 'days.id')
            ->where([
                    ['A.name', 'like', '%' . $from . '%'],
                    ['B.name', 'like', '%' . $to . '%'],
                    ['days.date', 'like', $date],
                ]
            )->get();
    }

    /**
     * @param $tripId
     * @param $scheduleId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null|static|static[]
     */
    public function selectTripForBooking($tripId, $scheduleId)
    {
        $this->scheduleId = $scheduleId;
        $trip = Trip::with(['bus', 'bus.busType', 'bus.bookedSeats' => function ($query) {
            $query->where(['schedule_id' => $this->scheduleId]);
        }, 'bus.bookedSeats.seat'])->find($tripId);
        $trip['schedule_id'] = $this->scheduleId;
        return $trip;
    }

    public function getSelectedTripDetails($tripId, $seatName){
        $this->tripId = $tripId;

        $trip =   Trip::with([
            'bus','bus.merchant'
        ])->select(['trips.id as trip_id','trips.price','A.name as from','B.name as to','trips.bus_id','days.date','trips.arrival_time','trips.depart_time','schedules.id as schedule_id'])
            ->join('locations as A','A.id','=','source')
            ->join('locations as B','B.id','=','destination')
            ->join('schedules','schedules.bus_id','=','trips.bus_id')
            ->join('days','schedules.day_id','=','days.id')
            ->where([
                    ['trips.id','like', $this->tripId],
                ]
            )->first();

        $trip->bus->seat_name = $seatName;

        return $trip;
    }

}