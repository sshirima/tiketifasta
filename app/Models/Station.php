<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'st_name';
    const COLUMN_ST_TYPES = 'st_type';
    const COLUMN_LOCATION_ID = 'location_id';
    const COLUMN_MERCHANT_ID = 'merchant_id';

    const TABLE = 'stations';

    const DEFAULT_STATIONS_TYPES=['boarding_point','dropping_point','bus_stand','bus_stop'];

    const DEFAULT_STATIONS_TYPES_NAMES=['boarding_point'=>'Boarding point',
        'dropping_point'=>'Dropping points',
        'bus_stand'=>'Bus stand',
        'bus_stop'=>'Bus stop'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_LOCATION_ID,self::COLUMN_MERCHANT_ID
    ];

    public static $rules = [
        self::COLUMN_NAME => 'required',
        self::COLUMN_ST_TYPES=> 'not_in:0',
        self::COLUMN_LOCATION_ID => 'not_in:0',
    ];

    public static $update_rules = [
        self::COLUMN_NAME => 'required',
        self::COLUMN_ST_TYPES=> 'not_in:0',
    ];

    public $timestamps = false;
}
