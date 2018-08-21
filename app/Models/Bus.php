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
        self::COLUMN_REG_NUMBER,self::COLUMN_CLASS,self::COLUMN_BUSTYPE_ID,self::COLUMN_STATE,self::COLUMN_MERCHANT_ID,
        self::COLUMN_DRIVER_NAME,self::COLUMN_CONDUCTOR_NAME,self::COLUMN_BUS_CONDITION
    ];

    public static $rules = [
        self::COLUMN_REG_NUMBER => 'required|max:255',
        self::COLUMN_BUSTYPE_ID => 'required|numeric|min:1',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route(){
        return $this->belongsTo(Route::class,self::COLUMN_ROUTE_ID,Route::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function busTrips(){
        return $this->hasMany(Trip::class,Trip::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules(){
        return $this->hasMany(Schedule::class,Schedule::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trips(){
        return $this->hasMany(Trip::class,Trip::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scheduledDays(){
        return $this->belongsToMany(Day::class,Schedule::TABLE, Schedule::COLUMN_BUS_ID, Schedule::COLUMN_DAY_ID)->withPivot(Schedule::COLUMN_DIRECTION);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sources(){
        return $this->belongsToMany(Location::class,Trip::TABLE, Trip::COLUMN_BUS_ID, Trip::COLUMN_SOURCE)
            ->withPivot(Trip::COLUMN_ID, Trip::COLUMN_PRICE,
                Trip::COLUMN_ARRIVAL_TIME,Trip::COLUMN_STATUS,Trip::COLUMN_DIRECTION,
                Trip::COLUMN_DEPART_TIME, Trip::COLUMN_TRAVELLING_DAYS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function destinations(){
        return $this->belongsToMany(Location::class,Trip::TABLE, Trip::COLUMN_BUS_ID, Trip::COLUMN_DESTINATION)
            ->withPivot(Trip::COLUMN_ID, Trip::COLUMN_PRICE,
                Trip::COLUMN_ARRIVAL_TIME,Trip::COLUMN_STATUS,Trip::COLUMN_DIRECTION,
                Trip::COLUMN_DEPART_TIME, Trip::COLUMN_TRAVELLING_DAYS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seats(){
        return $this->hasMany(Seat::class,Seat::COLUMN_BUS_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bookings(){
        return $this->hasManyThrough(Booking::class,Schedule::class, Schedule::COLUMN_BUS_ID, Booking::COLUMN_SCHEDULE_ID,Bus::COLUMN_ID,Schedule::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bookedSeats(){
        return $this->hasManyThrough(ScheduleSeat::class,Schedule::class, Schedule::COLUMN_BUS_ID, ScheduleSeat::COLUMN_SCHEDULE_ID,Bus::COLUMN_ID,Schedule::COLUMN_ID);
    }

    public function seatsCounts(){
        return $this->seats()->count();
    }

    public function seatArray($arrangement){
        $seats = $this->hasMany(Seat::class,'bus_id','id')->select(['seat_name','type','status'])->get();
        $array = array();

        foreach ($seats as $seat){
            $arrangement[$seat->seat_name]['status']= $seat->status;
            $arrangement[$seat->seat_name]['name']= $seat->seat_name;
            $array[$arrangement[$seat->seat_name]['index']]= $arrangement[$seat->seat_name];
        }
        return $array;
    }

    /**
     * Scope a query to only include active users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
