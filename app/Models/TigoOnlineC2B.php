<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TigoOnlineC2B extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_REFERENCE = 'reference';
    const COLUMN_PHONE_NUMBER = 'phone_number';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_CURRENCY = 'currency';
    const COLUMN_FIRST_NAME = 'firstname';
    const COLUMN_LAST_NAME = 'lastname';
    const COLUMN_EMAIL = 'email';
    const COLUMN_ACCESS_TOKEN = 'access_token';
    const COLUMN_AUTH_CODE = 'auth_code';
    const COLUMN_TAX = 'tax';
    const COLUMN_FEE = 'fee';
    const COLUMN_STATUS = 'status';
    const COLUMN_EXTERNAL_REF = 'external_ref_id';
    const COLUMN_MFS_ID = 'mfs_id';
    const COLUMN_ERROR_CODE = 'error_code';
    const COLUMN_BOOKING_PAYMENT_ID = 'booking_payment_id';
    const COLUMN_AUTHORIZED_AT = 'authorized_at ';

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

    const TABLE = 'tigoonline_c2b';

    const STATUS_FAIL = 'fail';
    const STATUS_SUCCESS = 'success';
    const STATUS_UNAUTHORIZED = 'unauthorized';

    protected $table = self::TABLE;

    protected $fillable = [
         self::COLUMN_REFERENCE,self::COLUMN_PHONE_NUMBER,self::COLUMN_AMOUNT,self::COLUMN_CURRENCY,self::COLUMN_FIRST_NAME,
        self::COLUMN_LAST_NAME,self::COLUMN_EMAIL,self::COLUMN_ACCESS_TOKEN,self::COLUMN_TAX,
        self::COLUMN_FEE,self::COLUMN_STATUS, self::COLUMN_BOOKING_PAYMENT_ID,self::COLUMN_TRANSACTION_STATUS
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bookingPayment(){
        return $this->belongsTo(BookingPayment::class,self::COLUMN_BOOKING_PAYMENT_ID,BookingPayment::COLUMN_ID);
    }
}
