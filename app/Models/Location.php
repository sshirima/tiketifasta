<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class Location extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_LOCATION_ID = 'location_id';

    const TABLE = 'locations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME
    ];

    public static $rules = [
       self::COLUMN_NAME=> 'required|max:255|unique:locations'
    ];

    public $timestamps = false;

    public static function getAllRecords($selections, $condition){

        if ($selections == null || empty($selections)){
            $selections = ['*'];
        }

        if ($condition == null || empty($condition)){
            DB::table(Location::TABLE)->select($selections)->get();
        } else {
            DB::table(Location::TABLE)->select($selections)->where($condition)->get();
        }
    }



    public static function getLocationById($id){
        return Location::find($id);
    }
}
