<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Route extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_ROUTE_NAME = 'route_name';
    const COLUMN_START_LOCATION = 'start_location';
    const COLUMN_END_LOCATION = 'end_location';
    const COLUMN_TRAVELLING_DAYS = 'travelling_days';
    const COLUMN_START_TIME = 'start_time';
    const COLUMN_END_TIME = 'end_time';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const COLUMN_ROUTE_ID = 'route_id';
    const REL_LOCATIONS= 'locations';

    const TABLE = 'routes';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const ROUTE_NAME = self::TABLE.'.'.self::COLUMN_ROUTE_NAME;
    const START_LOCATION = self::TABLE.'.'.self::COLUMN_START_LOCATION;
    const END_LOCATION = self::TABLE.'.'.self::COLUMN_END_LOCATION;
    const TRAVELLING_DAYS = self::TABLE.'.'.self::COLUMN_TRAVELLING_DAYS;
    const START_TIME = self::TABLE.'.'.self::COLUMN_START_TIME;
    const END_TIME = self::TABLE.'.'.self::COLUMN_END_TIME;
    const CREATED_AT = self::TABLE.'.'.self::COLUMN_CREATED_AT;
    const UPDATED_AT = self::TABLE.'.'.self::COLUMN_UPDATED_AT;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_ROUTE_NAME,self::COLUMN_START_LOCATION, self::COLUMN_END_LOCATION,self::COLUMN_TRAVELLING_DAYS,self::COLUMN_END_TIME
    ];

    public static $rules = [
        self::COLUMN_ROUTE_NAME => 'required|unique:routes',
        self::COLUMN_TRAVELLING_DAYS => 'not_in:0',
        self::COLUMN_START_LOCATION => 'not_in:0',
        'destinations.*' => 'not_in:0|distinct',
    ];

    public function startLocationName(){
        return $this->belongsTo(Location::class,self::COLUMN_START_LOCATION,Location::COLUMN_ID);
    }

    public function startLocation(){
        return $this->belongsTo(Location::class,self::COLUMN_START_LOCATION,Location::COLUMN_ID);
    }

    public function endLocation(){
        return $this->belongsTo(Location::class,self::COLUMN_END_LOCATION,Location::COLUMN_ID);
    }

    public function buses(){
        return $this->hasMany(Bus::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class,'location_route',self::COLUMN_ROUTE_ID,Location::COLUMN_LOCATION_ID);
    }
}
