<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class CancelScheduleComment extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_MESSAGE = 'message';
    const COLUMN_SCHEDULE_ID = 'schedule_id';

    const TABLE = 'cancel_schedule_comment';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_MESSAGE,self::COLUMN_SCHEDULE_ID
    ];

    public static $rules = [
       self::COLUMN_MESSAGE=> 'required'
    ];
}
