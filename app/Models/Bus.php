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
    const COLUMN_DRIVER_NAME = 'driver_name';
    const COLUMN_CONDUCTOR_NAME = 'conductor_name';
    const COLUMN_BUS_CONDITION = 'condition';
    const COLUMN_ROUTE_ID = 'route_id';

    const STATE_DEFAULT_ENABLED = 'ENABLED';
    const STATE_DEFAULT_DISABLED = 'DISABLED';
    const STATE_DEFAULT_SUSPENDED = 'SUSPENDED';

    const CONDITION_DEFAULT_OPERATIONAL = 'OPERATIONAL';
    const CONDITION_DEFAULT_MAINTANANCE = 'MAINTANANCE';
    const CONDITION_DEFAULT_ACCIDENT= 'ACCIDENT';

    const DEFAULT_CONDITIONS = [self::CONDITION_DEFAULT_OPERATIONAL,self::CONDITION_DEFAULT_MAINTANANCE,self::CONDITION_DEFAULT_ACCIDENT];

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const REG_NUMBER = self::TABLE.'.'.self::COLUMN_REG_NUMBER;
    const BUS_CLASS = self::TABLE.'.'.self::COLUMN_CLASS;
    const BUSTYPE_ID = self::TABLE.'.'.self::COLUMN_BUSTYPE_ID;
    const MERCHANT_ID = self::TABLE.'.'.self::COLUMN_MERCHANT_ID;
    const OPERATION_START = self::TABLE.'.'.self::COLUMN_OPERATION_START;
    const OPERATION_END = self::TABLE.'.'.self::COLUMN_OPERATION_END;
    const CREATED_AT = self::TABLE.'.'.self::COLUMN_CREATED_AT;
    const UPDATED_AT = self::TABLE.'.'.self::COLUMN_UPDATED_AT;
    const BUS_CONDITION = self::TABLE.'.'.self::COLUMN_BUS_CONDITION;
    const CONDUCTOR_NAME = self::TABLE.'.'.self::COLUMN_CONDUCTOR_NAME;
    const DRIVER_NAME = self::TABLE.'.'.self::COLUMN_DRIVER_NAME;
    const STATE = self::TABLE.'.'.self::COLUMN_STATE;
    const ROUTE_ID = self::TABLE.'.'.self::COLUMN_ROUTE_ID;

    const TABLE = 'buses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_REG_NUMBER,self::COLUMN_CLASS,self::COLUMN_BUSTYPE_ID,self::COLUMN_OPERATION_START,self::COLUMN_OPERATION_END
        ,self::COLUMN_STATE,self::COLUMN_MERCHANT_ID,self::COLUMN_DRIVER_NAME,self::COLUMN_CONDUCTOR_NAME,self::COLUMN_BUS_CONDITION
    ];

    public static $rules = [
        self::COLUMN_REG_NUMBER => 'required|max:255',
        self::COLUMN_BUSTYPE_ID => 'required|numeric|min:1',
        self::COLUMN_OPERATION_START => 'required|date',
        self::COLUMN_OPERATION_END => 'required|date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(){
        return $this->belongsTo(Merchant::class,self::COLUMN_MERCHANT_ID,Merchant::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function busType(){
        return $this->belongsTo(Bustype::class,self::COLUMN_BUSTYPE_ID,Bustype::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function busTrips(){
        return $this->hasMany(Trip::class,Trip::COLUMN_BUS_ID,self::COLUMN_ID);
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
