<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    const COLUMN_ID = 'id';
    const COLUMN_FIRST_NAME = 'firstname';
    const COLUMN_LAST_NAME = 'lastname';
    const COLUMN_PHONE_NUMBER = 'phonenumber';
    const COLUMN_EMAIL = 'email';
    const COLUMN_PASSWORD = 'password';
    const COLUMN_REMEMBER_TOKEN = 'remember_token';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const TABLE = 'admins';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [self::COLUMN_FIRST_NAME,
        self::COLUMN_LAST_NAME, self::COLUMN_PHONE_NUMBER, self::COLUMN_EMAIL, self::COLUMN_PASSWORD,
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::COLUMN_PASSWORD,self::COLUMN_REMEMBER_TOKEN,
    ];

    /**
     * Required values rules
     * @var array
     */
    public static $rules = [
        self::COLUMN_FIRST_NAME => 'required|max:255',
        self::COLUMN_LAST_NAME => 'required|max:255',
        self::COLUMN_EMAIL=> 'required',
        self::COLUMN_PASSWORD => 'required',
    ];
}
