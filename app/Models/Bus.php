<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bus extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_REG_NUMBER = 'reg_number';
    const COLUMN_CLASS = 'class';
    const COLUMN_BUSTYPE_ID = 'bustype_id';
    const COLUMN_MERCHANT_ID = 'merchant_id';
    const COLUMN_OPERATION_START = 'operation_start';
    const COLUMN_OPERATION_END = 'operation_end';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';
    const COLUMN_STATE = 'state';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const REG_NUMBER = self::TABLE.'.'.self::COLUMN_REG_NUMBER;
    const BUS_CLASS = self::TABLE.'.'.self::COLUMN_CLASS;
    const BUSTYPE_ID = self::TABLE.'.'.self::COLUMN_BUSTYPE_ID;
    const MERCHANT_ID = self::TABLE.'.'.self::COLUMN_MERCHANT_ID;
    const OPERATION_START = self::TABLE.'.'.self::COLUMN_OPERATION_START;
    const OPERATION_END = self::TABLE.'.'.self::COLUMN_OPERATION_END;
    const CREATED_AT = self::TABLE.'.'.self::COLUMN_CREATED_AT;
    const UPDATED_AT = self::TABLE.'.'.self::COLUMN_UPDATED_AT;
    const STATE = self::TABLE.'.'.self::COLUMN_STATE;

    const TABLE = 'buses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_REG_NUMBER,self::COLUMN_CLASS,self::COLUMN_BUSTYPE_ID,self::COLUMN_OPERATION_START,self::COLUMN_OPERATION_END
        ,self::COLUMN_STATE,self::COLUMN_MERCHANT_ID
    ];

    public static $rules = [
        self::COLUMN_REG_NUMBER => 'required|max:255',
        self::COLUMN_BUSTYPE_ID => 'required|numeric|min:1',
        self::COLUMN_OPERATION_START => 'required|date',
        self::COLUMN_OPERATION_END => 'required|date'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class,self::COLUMN_MERCHANT_ID,Merchant::COLUMN_ID);
    }

    public function busType(){
        return $this->belongsTo(Bustype::class,self::COLUMN_BUSTYPE_ID,Bustype::COLUMN_ID);
    }

    public function busRoutes(){
        return $this->hasMany(BusRoute::class,BusRoute::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    public function seats(){
        return $this->hasMany(Seat::class,Seat::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    public function reassignedSchedule(){
        return $this->belongsTo(ReassignBus::class,self::COLUMN_ID,ReassignBus::COLUMN_REASSIGNED_BUS_ID);
    }

    public function seatsCounts(){
        return $this->seats()->count();
    }

    public function seatArray($arrangement){
        $seats = $this->hasMany(Seat::class,'bus_id','id')->select(['seat_name','type','status'])->get();
        $array = array();

        foreach ($seats as $seat){
            $arrangement[$seat->seat_name]['status']= $seat->status;
            $arrangement[$seat->seat_name]['class']= $seat->type;
            $arrangement[$seat->seat_name]['name']= $seat->seat_name;
            $array[$arrangement[$seat->seat_name]['index']]= $arrangement[$seat->seat_name];
        }
        return $array;
    }

    /*public function ticketPrices(){
        return $this->hasManyThrough(TicketPrice::class,Timetables::class,'bus_id','timetable_id','id','id');
    }*/

    public function ticketPricesWithName($bus_id){
        return DB::table('ticketprices')->select(['ticketprices.id','ticketprices.ticket_type','ticketprices.price','ticketprices.updated_at','locations.name'])
            ->join('timetables','ticketprices.timetable_id','=','timetables.id')
            ->join('locations','timetables.location_id','=','locations.id')
            ->orderBy('locations.name','asc')
            ->where(['timetables.bus_id'=>$bus_id])->get();
    }

    public static function getScheduledBuses($startLocation, $destination, $travelDate){
        return DB::table('timetables')
            ->select(['merchants.name as merchant_name','buses.reg_number','bus_route.start_time as depart_time','timetables.arrival_time','timetables.id as timetable_id','buses.id as bus_id','ticketprices.price'])
            ->join('bus_route','timetables.busroute_id','=','bus_route.id')
            ->join('daily_timetables','bus_route.id','=','daily_timetables.bus_route_id')
            ->join('operation_days','operation_days.id','=','daily_timetables.operation_day_id')
            ->join('buses','bus_route.bus_id','=','buses.id')
            ->join('ticketprices','timetables.id','=','ticketprices.timetable_id')
            ->join('routes','routes.id','=','bus_route.route_id')
            ->join('merchants','merchants.id','=','buses.merchant_id')
            ->where(['ticketprices.ticket_type'=>'Adult'])
            ->where(['operation_days.date'=>$travelDate,'routes.start_location'=>$startLocation])
            ->where(['timetables.location_id'=>$destination,'routes.start_location'=>$startLocation])
            ->where('buses.operation_start','<=',$travelDate)
            ->where('buses.operation_end','>=',$travelDate)
            ->get();
    }
}
