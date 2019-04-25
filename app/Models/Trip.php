<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_SOURCE = 'source';
    const COLUMN_DESTINATION = 'destination';
    const COLUMN_DEPART_TIME = 'depart_time';
    const COLUMN_ARRIVAL_TIME = 'arrival_time';
    const COLUMN_TRAVELLING_DAYS = 'travelling_days';
    const COLUMN_BUS_ID = 'bus_id';
    const COLUMN_STATUS = 'status';
    const COLUMN_PRICE = 'price';
    const COLUMN_DIRECTION = 'direction';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const TRIP_DIRECTIONS = array('GO','RETURN','UNKNOWN');

    const TABLE = 'trips';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const SOURCE = self::TABLE.'.'.self::COLUMN_SOURCE;
    const DESTINATION = self::TABLE.'.'.self::COLUMN_DESTINATION;
    const DEPART_TIME = self::TABLE.'.'.self::COLUMN_DEPART_TIME;
    const ARRIVAL_TIME = self::TABLE.'.'.self::COLUMN_ARRIVAL_TIME;
    const TRAVELLING_DAYS = self::TABLE.'.'.self::COLUMN_TRAVELLING_DAYS;
    const BUS_ID = self::TABLE.'.'.self::COLUMN_BUS_ID;
    const STATUS = self::TABLE.'.'.self::COLUMN_STATUS;
    const PRICE = self::TABLE.'.'.self::COLUMN_PRICE;
    const DIRECTION = self::TABLE.'.'.self::COLUMN_DIRECTION;

    const REL_BUS_ROUTE = 'busRoute';
    const REL_SOURCE = 'source';
    const REL_DESTINATION = 'destination';
    const REL_TICKET_PRICE = 'ticketPrice';

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sources.*' => 'required',
        'destinations.*' => 'required',
        'arrival_times.*' => 'required',
        'depart_times.*' => 'required',
        'travelling_days.*' => 'required',
    ];

    public $table = self::TABLE;

    public $fillable = [
        self::COLUMN_SOURCE,
        self::COLUMN_DESTINATION,
        self::COLUMN_DEPART_TIME,
        self::COLUMN_ARRIVAL_TIME,
        self::COLUMN_TRAVELLING_DAYS,
        self::COLUMN_STATUS,
        self::COLUMN_BUS_ID,
        self::COLUMN_DIRECTION,
    ];

    public function from(){
        return $this->belongsTo(Location::class, self::COLUMN_SOURCE,Location::COLUMN_ID);
    }

    public function to(){
        return $this->belongsTo(Location::class, self::COLUMN_DESTINATION,Location::COLUMN_ID);
    }

    public function bus(){
        return $this->belongsTo(Bus::class,self::COLUMN_BUS_ID,Bus::COLUMN_ID);
    }

    public function droppingPoints(){
        return $this->hasMany(Station::class,Station::COLUMN_LOCATION_ID,self::COLUMN_DESTINATION);
    }

    public function boadingPoints(){
        return $this->hasMany(Station::class,Station::COLUMN_LOCATION_ID,self::COLUMN_SOURCE);
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
