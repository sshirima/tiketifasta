<?php
/**
 * Created by PhpStorm.
 * User: sshirima
 * Date: 6/1/2018
 * Time: 9:46 PM
 */

namespace App\Http\Controllers\Buses;


use App\Models\Bus;
use App\Models\Merchant;
use App\Models\Route;
use Illuminate\Http\Request;

trait BusRouteBaseController
{
    use BusRouteViewParams;

    protected $busRepository;
    protected $indexPage;
    protected $paramCreate = [];

    public function getRouteLocations(Request $request, $busId, $routeId){
        $route = Route::with(['locations:id,name'])->find($routeId);
        return response()->json($route->locations);
    }
}