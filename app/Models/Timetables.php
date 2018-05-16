<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Timetables extends Model
{
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'location_id' => 'required',
        'route_id' => 'required'
    ];
    public $timestamps = false;

    public $table = 'timetables';

    public $fillable = [
        'busroute_id',
        'location_id',
        'route_id',
        'arrival_time',
        'depart_time',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'location_id' => 'integer',
        'route_id' => 'integer'
    ];

    public function ticketPrice(){
        return $this->hasMany(TicketPrice::class,'timetable_id','id');
    }

    public function destinationLocation(){
        return $this->belongsTo(Location::class,'location_id','id');
    }

    public function busRoute(){
        return $this->belongsTo(BusRoute::class,'busroute_id','id');
    }

    public function ticketPriceByType(){
        $ticketPrices = $this->hasMany(TicketPrice::class,'timetable_id','id')->select(['ticket_type','price','seat_class'])->get();
        $array = array();
        foreach ($ticketPrices as $ticketPrice){
            $array[$ticketPrice->seat_class][$ticketPrice->ticket_type]['type'] = $ticketPrice->ticket_type;
            $array[$ticketPrice->seat_class][$ticketPrice->ticket_type]['price'] = $ticketPrice->price;
            $array[$ticketPrice->seat_class][$ticketPrice->ticket_type]['class'] = $ticketPrice->seat_class;
        }
        return $array;
    }

    public static function availableDestinations(){
        return DB::table('timetables')->select(['locations.id','locations.name'])
            ->join('locations','timetables.location_id','=','locations.id')
            ->get();
    }

    public static function availableDepartLocation(){
        return DB::table('timetables')->select(['locations.id','locations.name'])
            ->join('buses','timetables.bus_id','=','buses.id')
            ->join('routes','routes.id','=','buses.route_id')
            ->join('locations','routes.start_location','=','locations.id')
            ->get();
    }

    public static function locationsToArray($locations){
        $locations_array = array();
        foreach ($locations as $location){
            $locations_array[$location->id] = $location->name;
        }
        return $locations_array;
    }

    public static function timetableDetails($id, $seatName){
        $timetable = DB::table('timetables')
            ->select(['timetables.id','bus_route.bus_id','bus_route.start_time as depart_time','timetables.arrival_time','buses.reg_number',
                'merchants.name as company_name','seats.seat_name','A.price as ticket_price','seats.type as seat_type','A.ticket_type','seats.id as seat_id'])
            ->join('bus_route','bus_route.id','=','timetables.busroute_id')
            ->join('buses','bus_route.bus_id','=','buses.id')
            ->join('routes','bus_route.route_id','=','routes.id')
            ->join('seats','buses.id','=','seats.bus_id')
            ->join('ticketprices as A','A.timetable_id','=','timetables.id')
            ->join('ticketprices as B','B.seat_class','=','seats.type')
            ->join('merchants','buses.merchant_id','=','merchants.id')->where(['timetables.id'=>$id,'seats.seat_name'=>$seatName])->first();

        if (empty($timetable)){
            return null;
        }
        return $timetable;
    }
}
