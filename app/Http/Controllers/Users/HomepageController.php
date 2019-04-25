<?php
/**
 * Created by PhpStorm.
 * User: samson
 * Date: 3/11/2018
 * Time: 5:54 PM
 */

namespace App\Http\Controllers\Users;

use App\Models\Location;
use App\Models\Station;
use Illuminate\Http\Request;

class HomepageController extends BaseController
{

    public function autoCompleteLocationQuery(Request $request){
        $data = Location::select("name")
            ->where("name","LIKE","%{$request->input('query')}%")
            ->get();

        return response()->json($data);
    }

    public function autoCompleteStationQuery(Request $request, $id){
        $data = Station::select("st_name")
            ->where("name","LIKE","%{$request->input('query')}%")
            ->get();

        return response()->json($data);
    }
}