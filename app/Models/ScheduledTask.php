<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class ScheduledTask extends Model
{
    const COLUMN_TASK_ID = 'task_uid';
    const COLUMN_TASK_NAME = 'task_name';
    const COLUMN_TASK_DESCRIPTION = 'task_description';
    const COLUMN_TASK_RUN_INTERVAL = 'run_interval';
    const COLUMN_TASK_INTERVAL_UNIT = 'interval_unit';
    const COLUMN_TASK_RUN_STATUS = 'last_run_status';
    const COLUMN_LAST_RUN = 'last_run';

    const TABLE = 'scheduled_tasks';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_TASK_NAME, self::COLUMN_TASK_DESCRIPTION,self::COLUMN_TASK_ID,self::COLUMN_TASK_RUN_INTERVAL,self::COLUMN_TASK_INTERVAL_UNIT,
        self::COLUMN_TASK_RUN_STATUS,self::COLUMN_LAST_RUN
    ];

    public static function setTaskCompleted($task){
        $task->last_run_status = 'completed';
        $task->last_run = date('Y-m-d H:i:s');
        $task->update();
    }
}
