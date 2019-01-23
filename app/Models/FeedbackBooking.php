<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class FeedbackBooking extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_FEEDBACK_SCORE= 'score';
    const COLUMN_BOOKING_ID= 'booking_id';
    const COLUMN_USER_ID= 'user_id';

    const FEEDBACK_LEVEL= [1=>'Highly Dissatisfied',2=>'Dissatisfied',3=>'Neutral',4=>'Satisfied',5=>'Highly Satisfied'];

    const TABLE = 'feedback_bookings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_FEEDBACK_SCORE,self::COLUMN_BOOKING_ID,self::COLUMN_USER_ID,
    ];

    protected $table = self::TABLE;
}
