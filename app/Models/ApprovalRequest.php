<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ApprovalRequest extends Model
{
    public static $STAGE_ONE = 1;
    public static $STAGE_TWO = 2;
    public static $TYPE_BUS_ROUTE_ASSIGN = 'BUS_ROUTE_ASSIGN';
    public static $TYPE_SCHEDULE_CANCEL = 'SCHEDULE_CANCEL';
    public static $TYPE_SCHEDULE_REASSIGNMENT = 'SCHEDULE_REASSIGNMENT';
    public static $STATUS_PENDING = 'PENDING';
    public static $STATUS_APPROVED = 'APPROVED';
    public static $STATUS_SUSPENDED = 'SUSPENDED';
    public static $STATUS_REJECTED = 'REJECTED';

    const COLUMN_ID = 'id';
    const COLUMN_STAGE = 'stage';
    const COLUMN_TYPE = 'types';
    const COLUMN_STATUS = 'status';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const TABLE = 'approval_requests';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_STAGE,self::COLUMN_STATUS,self::COLUMN_TYPE
    ];

    protected $table= self::TABLE;
}
