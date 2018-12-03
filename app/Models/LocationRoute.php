<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class LocationRoute extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_LOCATION_ID = 'location_id';
    const COLUMN_ROUTE_ID = 'route_id';

    const TABLE = 'location_route';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_LOCATION_ID, self::COLUMN_ROUTE_ID
    ];

    public $timestamps = false;

}
