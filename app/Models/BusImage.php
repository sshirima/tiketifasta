<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class BusImage extends Model
{
    const COL_ID = 'id';
    const COL_NAME = 'image_name';
    const COL_BUS_ID = 'bus_id';

    const TABLE = 'bus_images';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COL_NAME,self::COL_BUS_ID,
    ];

    protected $table = self::TABLE;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus()
    {
        return $this->belongsTo(Bus::class, self::COL_BUS_ID, Bus::COLUMN_ID);
    }
}
