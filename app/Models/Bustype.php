<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bustype extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_NAME = 'name';
    const COLUMN_SEATS = 'seats';
    const COLUMN_ARRANGEMENT = 'seat_arrangement';

    const TABLE = 'bustypes';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_NAME, self::COLUMN_SEATS,self::COLUMN_ARRANGEMENT
    ];

    public static $rules = [
        self::COLUMN_NAME => 'required|max:255',
        self::COLUMN_SEATS => 'required|integer|max:127',
        self::COLUMN_ARRANGEMENT => 'required'
    ];

    public function buses(){
        return $this->hasMany(Bus::class,Bus::COLUMN_BUSTYPE_ID,self::COLUMN_ID);
    }
    public $timestamps = false;

    /**
     * @param $array
     * @return mixed
     */
    public static function getBusTypeSelectArray($array){

        $busTypes = Bustype::all();

        foreach ($busTypes as $busType) {
            $array[$busType[Bustype::COLUMN_ID]] = $busType[Bustype::COLUMN_NAME];
        }

        return $array;
    }
}
