<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ReassignBus extends Model
{

    const COLUMN_ID = 'id';
    const COLUMN_SCHEDULE_ID = 'schedule_id';
    const COLUMN_REASSIGNED_BUS_ID = 'reassigned_bus_id';
    const COLUMN_APPROVAL_REQUEST_ID = 'reassigned_bus_id';
    const COLUMN_STATUS = 'status';

    const TABLE = 'reassigned_buses';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_SCHEDULE_ID,self::COLUMN_REASSIGNED_BUS_ID,self::COLUMN_STATUS
    ];

    protected $table= self::TABLE;

    public function approvalRequest(){
        return $this->belongsTo(ApprovalRequest::class,self::COLUMN_APPROVAL_REQUEST_ID,ApprovalRequest::COLUMN_ID);
    }

}
