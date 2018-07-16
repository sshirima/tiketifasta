<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class Day extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_DATE = 'date';

    const ID = self::TABLE.'.'.'id';
    const DATE = self::TABLE.'.'.'date';

    const TABLE = 'days';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_DATE,
    ];

    protected $table = self::TABLE;

    public $timestamps = false;

    /**
     * @param $startingDate
     * @param $dates
     * @return mixed
     */
    protected static function nextDay($startingDate, $dates)
    {
        $startingDate->add(new \DateInterval('P1D'));
        $dates[$startingDate->format('Y-m-d')] = $startingDate->format('Y-m-d');
        return $dates;
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, Schedule::COLUMN_DAY_ID, self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function buses(){
        return $this->belongsToMany(Bus::class,Schedule::TABLE, Schedule::COLUMN_DAY_ID, Schedule::COLUMN_BUS_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function bookings(){
        return $this->hasManyThrough(Booking::class,Schedule::class,
            Schedule::COLUMN_DAY_ID, Booking::COLUMN_SCHEDULE_ID,Day::COLUMN_ID,Schedule::COLUMN_ID);
    }

    /**
     * @param int $interval
     * @return array
     */
    public static function getSchedulingDays($interval = 30){
        $dates = array();
        $startingDate = new DateTime("now");

        for ($i=0; $i<$interval; $i++){
            $dates = self::nextDay($startingDate, $dates);
        }

        return $dates;
    }
}
