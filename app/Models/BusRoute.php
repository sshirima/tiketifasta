<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BusRoute extends Model
{
    const COLUMN_ID = 'id';
    const COLUMN_BUS_ID = 'bus_id';
    const COLUMN_ROUTE_ID = 'route_id';
    const COLUMN_STATUS = 'status';

    const TABLE = 'bus_route';

    const ID = self::TABLE.'.'.self::COLUMN_ID;
    const BUS_ID = self::TABLE.'.'.self::COLUMN_BUS_ID;
    const ROUTE_ID = self::TABLE.'.'.self::COLUMN_ROUTE_ID;
    const STATUS = self::TABLE.'.'.self::COLUMN_STATUS;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        self::COLUMN_BUS_ID,self::COLUMN_ROUTE_ID,
    ];

    protected $table = self::TABLE;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function route(){
        return $this->belongsTo(Route::class, self::COLUMN_ROUTE_ID,Route::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bus(){
        return $this->belongsTo(Bus::class, self::COLUMN_BUS_ID,Bus::COLUMN_ID);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subRoutes(){
        return $this->hasMany(SubRoute::class,SubRoute::COLUMN_BUS_ROUTE_ID,self::COLUMN_ID);
    }

    /**
     * @param $bus_id
     * @param $merchant
     * @return array
     */
    public function getMerchantRoutesArray($bus_id=0, $merchant): array
    {
        $routes = array(0 => 'Select route');
        if ($bus_id == 0) {
            $busRoutes = $merchant->busRoutes()->select()->first()->with('route:id,route_name')->get();
        } else {
            $busRoutes = $merchant->busRoutes()->select()->first()->with('route:id,route_name')->where(['bus_route.bus_id' => $bus_id])->get();
        }
        foreach ($busRoutes as $busRoute) {
            $routes[$busRoute->route->id] = $busRoute->route->route_name;
        }
        return $routes;
    }
}
