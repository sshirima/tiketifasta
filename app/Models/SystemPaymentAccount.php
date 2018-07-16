<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemPaymentAccount extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_PAYMENT_MODE = 'payment_mode';
    const COLUMN_ACCOUNT_NUMBER = 'account_number';

    const TABLE = 'system_payment_accounts';

    public $table = self::TABLE;

    public $fillable = [
        self::COLUMN_PAYMENT_MODE,
        self::COLUMN_ACCOUNT_NUMBER,
    ];

    public $timestamps = false;
}
