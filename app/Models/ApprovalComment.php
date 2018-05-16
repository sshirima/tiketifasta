<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ApprovalComment extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_APPROVAL_REQUEST_ID = 'approval_request_id';
    const COLUMN_APPROVAL_STAGE = 'approval_stage';
    const COLUMN_CONTENT = 'content';
    const COLUMN_CREATED_AT = 'created_at';
    const COLUMN_UPDATED_AT = 'updated_at';

    const TABLE = 'approval_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_APPROVAL_REQUEST_ID,self::COLUMN_APPROVAL_STAGE,self::COLUMN_CONTENT
    ];

    protected $table= self::TABLE;

}
