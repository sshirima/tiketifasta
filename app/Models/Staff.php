<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use Notifiable;

    const COLUMN_ID = 'id';
    const COLUMN_FIRST_NAME = 'firstname';
    const COLUMN_LAST_NAME = 'lastname';
    const COLUMN_PHONE_NUMBER = 'phonenumber';
    const COLUMN_EMAIL = 'email';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_MERCHANT_ID = 'merchant_id';
    const COLUMN_REMEMBER_TOKEN = 'remember_token';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [self::COLUMN_FIRST_NAME,self::COLUMN_LAST_NAME, self::COLUMN_EMAIL, self::COLUMN_PASSWORD,self::COLUMN_PHONE_NUMBER,self::COLUMN_MERCHANT_ID];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [self::COLUMN_PASSWORD, self::COLUMN_REMEMBER_TOKEN];

    public static $rules = [
        self::COLUMN_FIRST_NAME => 'required|max:255',
        self::COLUMN_LAST_NAME => 'required|max:255',
        self::COLUMN_PHONE_NUMBER => 'required|max:255',
        self::COLUMN_EMAIL => 'required|string|email|max:255|unique:users',
        self::COLUMN_PASSWORD => 'required|string|min:6|confirmed'
    ];

    public function merchant(){
        return $this->belongsTo(Merchant::class, self::COLUMN_MERCHANT_ID,Merchant::COLUMN_ID);
    }
}
