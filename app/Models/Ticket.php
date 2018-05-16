<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_TRAVELLER_NAME = 'traveller_name';
    const COLUMN_TICKET_TYPE = 'ticket_type';
    const COLUMN_PRICE = 'price';
    const COLUMN_STATUS = 'status';
    const COLUMN_USER_ID = 'user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       self::COLUMN_TRAVELLER_NAME,self::COLUMN_TICKET_TYPE,self::COLUMN_PRICE,self::COLUMN_STATUS,self::COLUMN_USER_ID
    ];

    public static $rules = [
        self::COLUMN_TRAVELLER_NAME => 'required|max:255',
        self::COLUMN_PRICE => 'required',
        self::COLUMN_TICKET_TYPE => 'required',
        self::COLUMN_STATUS => 'required',
    ];

    public function user(){
        return $this->belongsTo(User::class,self::COLUMN_USER_ID,User::COLUMN_ID);
    }
}
