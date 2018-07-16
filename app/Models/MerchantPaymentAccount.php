<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantPaymentAccount extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_PAYMENT_MODE = 'payment_mode';
    const COLUMN_ACCOUNT_NUMBER = 'account_number';
    const COLUMN_MERCHANT_ID = 'merchant_id';

    const TABLE = 'merchant_payment_accounts';

    public $table = self::TABLE;

    public $fillable = [
        self::COLUMN_PAYMENT_MODE,
        self::COLUMN_ACCOUNT_NUMBER,
        self::COLUMN_MERCHANT_ID,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchant(){
        return $this->belongsTo(Location::class, self::COLUMN_MERCHANT_ID,Merchant::COLUMN_ID);
    }

    public $timestamps = false;
}
