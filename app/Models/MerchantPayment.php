<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 7/9/2018
 * Time: 7:30 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MerchantPayment extends Model
{

    const COLUMN_ID = 'id';
    const COLUMN_PAYMENT_REF = 'payment_ref';
    const COLUMN_NET_AMOUNT = 'net_amount';
    const COLUMN_COMMISSION_AMOUNT = 'commission_amount';
    const COLUMN_MERCHANT_AMOUNT = 'merchant_amount';
    const COLUMN_PAYMENT_ACCOUNT_ID = 'payment_account_id';
    const COLUMN_PAYMENT_MODE = 'payment_mode';
    const COLUMN_PAYMENT_STAGE = 'payment_stage';
    const COLUMN_TRANSFER_STATUS = 'transfer_status';

    const PAYMENT_STATUS = ['PROCESSING_INITIATED','TRANSFER_INITIATED','TRANSFER_FAIL','TRANSFER_SUCCESS'];

    const TABLE = 'merchant_payments';

    protected $table = self::TABLE;

    protected $fillable = [
        self::COLUMN_PAYMENT_REF, self::COLUMN_NET_AMOUNT,self::COLUMN_COMMISSION_AMOUNT,self::COLUMN_MERCHANT_AMOUNT,
        self::COLUMN_PAYMENT_ACCOUNT_ID,self::COLUMN_PAYMENT_MODE,self::COLUMN_PAYMENT_STAGE,self::COLUMN_TRANSFER_STATUS
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function merchantPaymentAccount(){
        return $this->belongsTo(MerchantPaymentAccount::class,self::COLUMN_PAYMENT_ACCOUNT_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookingPayments(){
        return $this->hasMany(BookingPayment::class,BookingPayment::COLUMN_MERCHANT_PAY_ID, self::COLUMN_ID);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tigoB2C(){
        return $this->hasOne(TigoB2C::class,TigoB2C::COLUMN_MERCHANT_PAY_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mpesaB2C(){
        return $this->hasOne(MpesaB2C::class,MpesaB2C::COLUMN_MERCHANT_PAY_ID);
    }
}