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

    const TABLE = 'tigo_b2c';

    protected $table = self::TABLE;

    protected $fillable = [
        self::COLUMN_MSISDN, self::COLUMN_MSISDN1,self::COLUMN_REFERENCE, self::COLUMN_LANGUAGE, self::COLUMN_AMOUNT,self::COLUMN_TXN_STATUS,
        self::COLUMN_TXN_ID,self::COLUMN_MESSAGE
    ];
}
