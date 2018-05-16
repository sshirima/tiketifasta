<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubRoute extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_SOURCE = 'source';
    const COLUMN_DESTINATION = 'destination';
    const COLUMN_DEPART_TIME = 'depart_time';
    const COLUMN_ARRIVAL_TIME = 'arrival_time';
    const COLUMN_TRAVELLING_DAYS = 'travelling_days';
    const COLUMN_BUS_ROUTE_ID = 'bus_route_id';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const TABLE = 'subroutes';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const SOURCE = self::TABLE.'.'.self::COLUMN_SOURCE;
    const DESTINATION = self::TABLE.'.'.self::COLUMN_DESTINATION;
    const DEPART_TIME = self::TABLE.'.'.self::COLUMN_DEPART_TIME;
    const ARRIVAL_TIME = self::TABLE.'.'.self::COLUMN_ARRIVAL_TIME;
    const TRAVELLING_DAYS = self::TABLE.'.'.self::COLUMN_TRAVELLING_DAYS;
    const BUS_ROUTE_ID = self::TABLE.'.'.self::COLUMN_BUS_ROUTE_ID;

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
        self::COLUMN_BUS_ROUTE_ID,
    ];

    public function busRoute(){
        return $this->belongsTo(BusRoute::class, self::COLUMN_BUS_ROUTE_ID,BusRoute::COLUMN_ID);
    }

    public function source(){
        return $this->belongsTo(Location::class, self::COLUMN_SOURCE,Location::COLUMN_ID);
    }

    public function sourceLocation(){
        return $this->belongsTo(Location::class, self::COLUMN_SOURCE,Location::COLUMN_ID);
    }

    public function destination(){
        return $this->belongsTo(Location::class, self::COLUMN_DESTINATION,Location::COLUMN_ID);
    }

    public function destinationLocation(){
        return $this->belongsTo(Location::class, self::COLUMN_DESTINATION,Location::COLUMN_ID);
    }

    public function ticketPrice(){
        return $this->hasOne(TicketPrice::class, TicketPrice::COLUMN_SUB_ROUTE_ID,self::COLUMN_ID);
    }
}
