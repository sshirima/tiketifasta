<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaC2B extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_COMMAND_ID = 'command_id';
    const COLUMN_MSISDN = 'msisdn';
    const COLUMN_INITIATOR = 'initiator';
    const COLUMN_OG_CONVERSATION_ID = 'og_conversation_id';
    const COLUMN_RECIPIENT = 'recipient';
    const COLUMN_MPESA_RECEIPT = 'mpesa_receipt';
    const COLUMN_ACCOUNT_REFERENCE = 'account_reference';
    const COLUMN_BOOKING_PAYMENT_ID = 'booking_payment_id';
    const COLUMN_TRANSACTION_ID = 'transaction_id';
    const COLUMN_SERVICE_RECEIPT = 'service_receipt';
    const COLUMN_SERVICE_DATE = 'service_date';
    const COLUMN_CONVERSATION_ID = 'conversation_id';
    const COLUMN_STAGE = 'stage';
    const COLUMN_SERVICE_STATUS = 'service_status';
    const COLUMN_AUTHORIZED_AT = 'authorized_at';

    const TABLE = 'mpesa_c2b';

    const STATUS_PENDING = 'pending';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CONFIRMED = 'confirmed';

    protected $table = self::TABLE;

    protected $fillable = [
         self::COLUMN_AMOUNT,self::COLUMN_MSISDN,self::COLUMN_COMMAND_ID,self::COLUMN_INITIATOR,self::COLUMN_OG_CONVERSATION_ID,self::COLUMN_RECIPIENT,self::COLUMN_MPESA_RECEIPT,
        self::COLUMN_ACCOUNT_REFERENCE,self::COLUMN_TRANSACTION_ID,self::COLUMN_SERVICE_RECEIPT,self::COLUMN_SERVICE_DATE,self::COLUMN_CONVERSATION_ID,
        self::COLUMN_STAGE,self::COLUMN_SERVICE_STATUS,self::COLUMN_AUTHORIZED_AT,self::COLUMN_BOOKING_PAYMENT_ID
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bookingPayment(){
        return $this->belongsTo(BookingPayment::class,self::COLUMN_BOOKING_PAYMENT_ID,BookingPayment::COLUMN_ID);
    }
}
