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
    const COLUMN_WORKING_ACCOUNT_FUNDS = 'working_account_funds';
    const COLUMN_UTILITY_ACCOUNT_FUNDS = 'utility_account_funds';
    const COLUMN_CHARGES_PAID_FUNDS = 'charges_paid_funds';

    const TABLE = 'mpesa_b2c';

    protected $table = self::TABLE;

    protected $fillable = [
         self::COLUMN_AMOUNT,self::COLUMN_RECIPIENT,self::COLUMN_COMMAND_ID,self::COLUMN_INITIATOR,self::COLUMN_OG_CONVERSATION_ID,self::COLUMN_RECIPIENT,self::COLUMN_MPESA_RECEIPT,
        self::COLUMN_TRANSACTION_ID,self::COLUMN_TRANSACTION_DATE,self::COLUMN_RESULT_TYPE,self::COLUMN_CONVERSATION_ID,
        self::COLUMN_RESULT_CODE,self::COLUMN_WORKING_ACCOUNT_FUNDS,self::COLUMN_UTILITY_ACCOUNT_FUNDS,self::COLUMN_CHARGES_PAID_FUNDS, self::COLUMN_RESULT_DESC
    ];
}
