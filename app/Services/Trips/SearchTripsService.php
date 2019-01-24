<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/8/2018
 * Time: 4:04 PM
 */

namespace App\Services\Trips;



use App\Models\Bus;
use App\Models\Schedule;
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
        $conditions = array();

        if (isset($from)){
            $conditions[] = ['A.name', 'like', '%' . $from . '%'];
        }

        if (isset($to)){
            $conditions[] = ['B.name', 'like', '%' . $to . '%'];
        }

        if (isset($date)){
            $conditions[] = ['days.date', 'like', $date];
        }

        $conditions[] = ['schedules.status', '=', 1];


        $results = Schedule::select('trips.id as trip_id', 'A.name as from', 'trips.price', 'B.name as to',
            'trips.bus_id', 'days.date', 'trips.arrival_time', 'trips.depart_time', 'schedules.id as schedule_id')
            ->join('days','days.id','=','schedules.day_id')
            ->join('buses','buses.id','=','schedules.bus_id')
            ->join('trips', function ($join){
                $join->on('trips.bus_id','=','schedules.bus_id');
                $join->on('trips.direction','=','schedules.direction');
            })
            ->join('locations as A', 'A.id', '=', 'trips.source')
            ->join('locations as B', 'B.id', '=', 'trips.destination')
            ->where($conditions)->get();

        foreach ($results as $result){
            $images = $result->bus->images;
            $url = '[%s]';
            if(count($images) > 0){
                $imgs = '';
                foreach ($images as $key=>$image){
                    //$result->bus->images[] = asset('images/buses/').'/T547DFG_1.png';
                    if ($key == 0){
                        $imgs = '"'.asset('images/buses/').'/'.$image->image_name.'"';
                    } else {
                        $imgs = $imgs. ',"'.asset('images/buses/').'/'.$image->image_name.'"';
                    }
                }
                $result->bus->image_urls = sprintf($url,$imgs);
            } else {
                $result->bus->image_urls = sprintf($url,'"'.asset('images/buses/').'/no_picture.png"' );
                //$result->bus->image_urls[] = asset('images/buses/').'T547DFG_1.png';
            }
        }

        return $results;
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

    public function getSelectedTripDetails($scheduleId, $tripId, $seatName){
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
                    ['schedules.id','=', $scheduleId],
                ]
            )->first();

        $trip->bus->seat_name = $seatName;

        return $trip;
    }

}