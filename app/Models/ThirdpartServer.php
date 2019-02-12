<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdpartServer extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_IP_ADDRESS = 'ip_address';
    const COLUMN_SERVER_NAME = 'server_name';
    const COLUMN_PORT = 'port_number';
    const COLUMN_AVAILABILITY_STATUS = 'availability_status';

    const TABLE = 'third_part_servers';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_IP_ADDRESS, self::COLUMN_SERVER_NAME,self::COLUMN_PORT,self::COLUMN_AVAILABILITY_STATUS
    ];
}
