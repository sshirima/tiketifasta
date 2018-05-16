<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPriceDefault extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_START_LOCATION = 'start_location';
    const COLUMN_LAST_LOCATION = 'last_location';
    const COLUMN_BUS_CLASS_ID = 'bus_class_id';
    const COLUMN_PRICE = 'price';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATE_AT = 'updated_at';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const START_LOCATION = self::TABLE.'.'.self::COLUMN_START_LOCATION;
    const LAST_LOCATION = self::TABLE.'.'.self::COLUMN_LAST_LOCATION;
    const BUS_CLASS_ID = self::TABLE.'.'.self::COLUMN_BUS_CLASS_ID;
    const PRICE = self::TABLE.'.'.self::COLUMN_PRICE;

    const TABLE = 'ticket_price_default';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_START_LOCATION,self::COLUMN_PRICE,self::COLUMN_LAST_LOCATION,self::COLUMN_BUS_CLASS_ID
    ];

    public static $rules = [
        self::COLUMN_START_LOCATION => 'required|min:1',
        self::COLUMN_LAST_LOCATION=> 'required|min:1',
        self::COLUMN_PRICE => 'required|min:1',
    ];

    protected $table = self::TABLE;

    public function busClass(){
        return $this->hasOne(BusClass::class,BusClass::COLUMN_ID, self::COLUMN_BUS_CLASS_ID);
    }

    public function startLocation(){
        return $this->belongsTo(Location::class,self::COLUMN_START_LOCATION, Location::COLUMN_ID);
    }

    public function lastLocation(){
        return $this->belongsTo(Location::class,self::COLUMN_LAST_LOCATION, Location::COLUMN_ID);
    }
}
