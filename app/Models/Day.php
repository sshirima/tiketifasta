<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Day extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_DATE = 'date';

    const ID = self::TABLE.'.'.'id';
    const DATE = self::TABLE.'.'.'date';

    const TABLE = 'days';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_DATE,
    ];

    protected $table = self::TABLE;

    public $timestamps = false;

    public function schedules()
    {
        return $this->hasMany(Schedule::class, Schedule::COLUMN_DAY_ID, self::COLUMN_ID);
    }
}
