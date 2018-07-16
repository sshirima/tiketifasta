<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class ScheduleSeat extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_SEAT_ID = 'seat_id';
    const COLUMN_SCHEDULE_ID = 'schedule_id';
    const COLUMN_STATUS = 'status';

    const TABLE = 'schedule_seat';

    protected $table = self::TABLE;

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const SEAT_ID = self::TABLE.'.'.self::COLUMN_SEAT_ID;
    const SCHEDULE_ID = self::TABLE.'.'.self::COLUMN_SCHEDULE_ID;
    const STATUS = self::TABLE.'.'.self::COLUMN_STATUS;

    const STATUES = ['available'=>'Available',
        'unavailable'=>'Unavailable',
        'booked'=>'Booked',
        'suspended'=>'Booked',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_SEAT_ID,self::COLUMN_SCHEDULE_ID,self::COLUMN_STATUS
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seat(){
        return $this->belongsTo(Seat::class,self::COLUMN_SEAT_ID,Seat::COLUMN_ID);
    }
}
