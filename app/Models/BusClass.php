<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class BusClass extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_CLASS_NAME = 'class_name';

    const TABLE = 'bus_classes';

    const ID = self::TABLE.'.'.'id';
    const NAME = self::TABLE.'.'.'class_name';

    const DEFAULT_CLASS_NAME = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_CLASS_NAME
    ];

    public static $rules = [
       self::COLUMN_CLASS_NAME=> 'required|max:255|unique:'.self::TABLE
    ];

    protected $table = self::TABLE;

    public $timestamps = false;
}
