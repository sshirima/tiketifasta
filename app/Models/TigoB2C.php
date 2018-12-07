<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TigoB2C extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_REFERENCE = 'reference_id';
    const COLUMN_MSISDN = 'msisdn';
    const COLUMN_MSISDN1 = 'msisdn1';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_LANGUAGE = 'language';
    const COLUMN_TXN_STATUS = 'txn_status';
    const COLUMN_TXN_ID = 'txn_id';
    const COLUMN_MESSAGE = 'txn_message';
    const COLUMN_MERCHANT_PAY_ID = 'merchant_pay_id';

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

    const TABLE = 'tigo_b2c';

    const ERROR_CODE_001 = '0x001';
    const ERROR_DESC_001 = 'Connection timeout';

    protected $table = self::TABLE;

    protected $fillable = [
        self::COLUMN_MSISDN, self::COLUMN_MSISDN1,self::COLUMN_REFERENCE, self::COLUMN_LANGUAGE, self::COLUMN_AMOUNT,self::COLUMN_TXN_STATUS,
        self::COLUMN_TXN_ID,self::COLUMN_MESSAGE,self::COLUMN_TRANSACTION_STATUS
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchantPayment(){
        return $this->belongsTo(MerchantPayment::class,self::COLUMN_MERCHANT_PAY_ID, MerchantPayment::COLUMN_ID);
    }
}
