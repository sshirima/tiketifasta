<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 7:30 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class BookingPayment extends Model
{

    const COLUMN_ID = 'id';
    const COLUMN_PAYMENT_REF = 'payment_ref';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_BOOKING_ID = 'booking_id';
    const COLUMN_PAYMENT_METHOD = 'method';
    const COLUMN_PHONE_NUMBER = 'phone_number';

    const TABLE = 'booking_payments';

    protected $table = self::TABLE;

    protected $fillable = [
        self::COLUMN_PAYMENT_REF, self::COLUMN_AMOUNT,self::COLUMN_BOOKING_ID,self::COLUMN_PAYMENT_METHOD,self::COLUMN_PHONE_NUMBER
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking(){
        return $this->belongsTo(Booking::class,self::COLUMN_BOOKING_ID,Booking::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ticket(){
        return $this->hasOne(Ticket::class,Ticket::COLUMN_PAYMENT_ID,self::COLUMN_ID);
    }
}