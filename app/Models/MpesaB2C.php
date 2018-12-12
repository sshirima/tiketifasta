<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MpesaB2C extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_COMMAND_ID = 'command_id';
    const COLUMN_INITIATOR = 'initiator';
    const COLUMN_RECIPIENT = 'recipient';
    const COLUMN_TRANSACTION_DATE = 'transaction_date';
    const COLUMN_TRANSACTION_ID = 'transaction_id';
    const COLUMN_CONVERSATION_ID = 'conversation_id';
    const COLUMN_OG_CONVERSATION_ID = 'og_conversation_id';
    const COLUMN_MPESA_RECEIPT = 'mpesa_receipt';
    const COLUMN_RESULT_TYPE = 'result_type';
    const COLUMN_RESULT_CODE = 'result_code';
    const COLUMN_RESULT_DESC = 'result_desc';
    const COLUMN_INIT_CODE = 'init_code';
    const COLUMN_INIT_DESC = 'init_desc';
    const COLUMN_CONFIRM_CODE = 'confirm_code';
    const COLUMN_CONFIRM_DESC = 'confirm_desc';
    const COLUMN_STATUS = 'status';
    const COLUMN_MERCHANT_PAY_ID = 'merchant_payment_id';
    const COLUMN_WORKING_ACCOUNT_FUNDS = 'working_account_funds';
    const COLUMN_UTILITY_ACCOUNT_FUNDS = 'utility_account_funds';
    const COLUMN_CHARGES_PAID_FUNDS = 'charges_paid_funds';

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

    const TABLE = 'mpesa_b2c';

    const STATUS_LEVEL = ['INITIALIZATION_SUCCESS','INITIALIZATION_FAIL','CONFIRMATION_SUCCESS','CONFIRMATION_FAIL'];

    const ERROR_CODE_001 = '0x001';
    const ERROR_DESC_001 = 'Connection timeout';

    protected $table = self::TABLE;

    protected $fillable = [
         self::COLUMN_AMOUNT,self::COLUMN_RECIPIENT,self::COLUMN_COMMAND_ID,self::COLUMN_INITIATOR,self::COLUMN_OG_CONVERSATION_ID,self::COLUMN_RECIPIENT,self::COLUMN_MPESA_RECEIPT,
        self::COLUMN_TRANSACTION_ID,self::COLUMN_TRANSACTION_DATE,self::COLUMN_RESULT_TYPE,self::COLUMN_CONVERSATION_ID,
        self::COLUMN_RESULT_CODE,self::COLUMN_WORKING_ACCOUNT_FUNDS,self::COLUMN_UTILITY_ACCOUNT_FUNDS,self::COLUMN_TRANSACTION_STATUS,
        self::COLUMN_CHARGES_PAID_FUNDS, self::COLUMN_RESULT_DESC,self::COLUMN_MERCHANT_PAY_ID
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchantPayment(){
        return $this->belongsTo(MerchantPayment::class,self::COLUMN_MERCHANT_PAY_ID, MerchantPayment::COLUMN_ID);
    }
}
