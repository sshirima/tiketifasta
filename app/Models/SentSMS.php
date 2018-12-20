<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class SentSMS extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_RECEIVER = 'receiver';
    const COLUMN_SENDER = 'sender';
    const COLUMN_MESSAGE = 'message';
    const COLUMN_OPERATOR = 'operator';
    const COLUMN_IS_SENT = 'is_sent';

    const OPERATORS = ['VODACOM','TIGO','AIRTEL','HALOTEL'];

    const OPERATOR_VODACOM = 'VODACOM';
    const OPERATOR_TIGO = 'TIGO';

    const TABLE = 'sent_sms';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_RECEIVER,self::COLUMN_SENDER,self::COLUMN_MESSAGE,self::COLUMN_OPERATOR
    ];

    protected $table = self::TABLE;
}
