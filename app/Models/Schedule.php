<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Okipa\LaravelBootstrapTableList\TableList;

class Schedule extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_BUS_ROUTE_ID = 'bus_route_id';
    const COLUMN_DAY_ID = 'day_id';
    const COLUMN_BUS_ID = 'bus_id';
    const COLUMN_STATUS = 'status';
    const COLUMN_DIRECTION = 'direction';

    const ID = self::TABLE.'.'.'id';
    const BUS_ROUTE_ID = self::TABLE.'.'.'bus_route_id';
    const DAY_ID = self::TABLE.'.'.'day_id';
    const STATUS = self::TABLE.'.'.'status';
    const DIRECTION = self::TABLE.'.'.self::COLUMN_DIRECTION;

    const TABLE = 'schedules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_BUS_ID, self::COLUMN_DAY_ID,self::COLUMN_STATUS,self::COLUMN_DIRECTION
    ];

    public $timestamps = false;

    public $conditions = array();

    public function day(){
        return $this->belongsTo(Day::class,self::COLUMN_DAY_ID, Day::COLUMN_ID);
    }

    public function date(){
        return $this->belongsTo(Day::class,self::COLUMN_DAY_ID, Day::COLUMN_ID);
    }

    public function bookings(){
        return $this->hasMany(Booking::class,Booking::COLUMN_SCHEDULE_ID, self::COLUMN_ID);
    }

    public function bus(){
        return $this->belongsTo(Bus::class,self::COLUMN_BUS_ID,Bus::COLUMN_ID);
    }



    public static function notScheduled($busId, $dayId){
        $routes = DB::table(BusRoute::TABLE)
            ->join(Schedule::TABLE,Schedule::BUS_ROUTE_ID,'=',BusRoute::ID)
            ->where([BusRoute::BUS_ID=>$busId, Schedule::DAY_ID=>$dayId])->get();
        if ($routes->isEmpty()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGoing($query)
    {
        return $query->where(self::COLUMN_DIRECTION, '=', 'GO');
    }

    /**
     * Scope a query to only include popular users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReturning($query)
    {
        return $query->where(self::COLUMN_DIRECTION, '=', 'RETURN');
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
