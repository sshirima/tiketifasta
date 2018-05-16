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
    const COLUMN_STATUS = 'status';

    const ID = self::TABLE.'.'.'id';
    const BUS_ROUTE_ID = self::TABLE.'.'.'bus_route_id';
    const DAY_ID = self::TABLE.'.'.'day_id';
    const STATUS = self::TABLE.'.'.'status';

    const TABLE = 'schedules';
    public $timestamps = false;

    public $conditions = array();

    public function day(){
        return $this->belongsTo(Day::class,self::COLUMN_DAY_ID, Day::COLUMN_ID);
    }

    public function bookings(){
        return $this->hasMany(Booking::class,Booking::COLUMN_SCHEDULE_ID, self::COLUMN_ID);
    }

    public function busRoute(){
        return $this->belongsTo(BusRoute::class,self::COLUMN_BUS_ROUTE_ID,BusRoute::COLUMN_ID);
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_BUS_ROUTE_ID,self::COLUMN_DAY_ID,self::COLUMN_STATUS
    ];
}
