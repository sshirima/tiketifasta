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
    const COLUMN_MERCHANT_PAY_ID = 'merchant_payment_id';
    const COLUMN_PHONE_NUMBER = 'phone_number';
    const COLUMN_TRANSACTION_STATUS = 'transaction_status';

    const TRANS_STATUS_AUTHORIZED = 'authorized';
    const TRANS_STATUS_PENDING = 'pending';
    const TRANS_STATUS_FAILED = 'failed';
    const TRANS_STATUS_VOIDED = 'voided';
    const TRANS_STATUS_POSTED = 'posted';
    const TRANS_STATUS_SETTLED = 'settled';
    const TRANS_STATUS_REFUND_POSTED= 'refund_posted';
    const TRANS_STATUS_REFUND_SETTLED = 'refund_settled';
    const TRANS_STATUS_REFUNDED = 'refunded';

    const TRANS_STATUES = [self::TRANS_STATUS_AUTHORIZED, self::TRANS_STATUS_PENDING, self::TRANS_STATUS_FAILED,
        self::TRANS_STATUS_VOIDED,self::TRANS_STATUS_POSTED,self::TRANS_STATUS_SETTLED, self::TRANS_STATUS_REFUND_POSTED,
        self::TRANS_STATUS_REFUND_SETTLED, self::TRANS_STATUS_REFUNDED];

    const TABLE = 'booking_payments';

    protected $table = self::TABLE;

    protected $fillable = [
        self::COLUMN_PAYMENT_REF,self::COLUMN_TRANSACTION_STATUS, self::COLUMN_AMOUNT,self::COLUMN_BOOKING_ID,self::COLUMN_PAYMENT_METHOD,self::COLUMN_PHONE_NUMBER
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mpesaC2B(){
        return $this->hasOne(MpesaC2B::class,MpesaC2B::COLUMN_BOOKING_PAYMENT_ID,self::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tigoC2B(){
        return $this->hasOne(TigoOnlineC2B::class,TigoOnlineC2B::COLUMN_BOOKING_PAYMENT_ID,self::COLUMN_ID);
    }
}