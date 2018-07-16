<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_TICKET_REFERENCE = 'ticket_ref';
    const COLUMN_BOOKING_ID = 'booking_id';
    const COLUMN_PRICE = 'price';
    const COLUMN_PAYMENT_ID = 'payment_id';
    const COLUMN_STATUS = 'status';
    const COLUMN_USER_ID = 'user_id';

    const TABLE = 'tickets';

    const STATUS_VALID = 'VALID';
    const STATUS_CONFIRMED = 'CONFIRMED';
    const STATUS_EXPIRED = 'EXPIRED';
    const STATUSES = array('VALID','CONFIRMED','EXPIRED');

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       self::COLUMN_BOOKING_ID,self::COLUMN_TICKET_REFERENCE,self::COLUMN_PAYMENT_ID,self::COLUMN_PRICE,
        self::COLUMN_STATUS,self::COLUMN_USER_ID
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class,self::COLUMN_USER_ID,User::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking(){
        return $this->belongsTo(Booking::class,self::COLUMN_BOOKING_ID,Booking::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bookingPayment(){
        return $this->belongsTo(BookingPayment::class,self::COLUMN_PAYMENT_ID,BookingPayment::COLUMN_ID);
    }
}
