<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketPrice extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_TICKET_TYPE = 'ticket_type';
    const COLUMN_SUB_ROUTE_ID = 'sub_route_id';
    const COLUMN_PRICE = 'price';
    const COLUMN_STATUS = 'status';
    const COLUMN_SEAT_CLASS = 'seat_class';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATE_AT = 'updated_at';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const TICKET_TYPE = self::TABLE.'.'.self::COLUMN_TICKET_TYPE;
    const SUB_ROUTE_ID = self::TABLE.'.'.self::COLUMN_SUB_ROUTE_ID;
    const PRICE = self::TABLE.'.'.self::COLUMN_PRICE;
    const STATUS = self::TABLE.'.'.self::COLUMN_STATUS;
    const SEAT_CLASS = self::TABLE.'.'.self::COLUMN_SEAT_CLASS;

    const TABLE = 'ticketprices';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_TICKET_TYPE,self::COLUMN_PRICE,self::COLUMN_STATUS,self::COLUMN_SEAT_CLASS,self::COLUMN_SUB_ROUTE_ID
    ];

    public static $rules = [
        self::COLUMN_TICKET_TYPE => 'required',
        self::COLUMN_PRICE => 'required',
    ];

    protected $table = self::TABLE;
}
